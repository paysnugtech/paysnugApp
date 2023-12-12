<?php 


namespace App\Implementations\Services;

use App\Enums\ServiceTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Http\Resources\TransfersResource;
use App\Http\Resources\UsersResource;
use App\Http\Resources\WalletsResource;
use App\Interfaces\Repositories\IBankBeneficiaryRepository;
use App\Interfaces\Repositories\IBankRepository;
use App\Interfaces\Repositories\IBankTransferRepository;
use App\Interfaces\Repositories\ILevelRepository;
use App\Interfaces\Repositories\IServiceTypeRepository;
use App\Interfaces\Repositories\ITransactionRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Services\ITransferService;
use App\Models\BankBeneficiary;
use App\Models\BankTransfer;
use App\Models\Transaction;
use App\Traits\EmailTrait;
use App\Traits\GenerateTransactionNo;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Policies\UserPolicy;
use App\Traits\WhereHouseTrait;

class TransferService implements ITransferService
{
    use EmailTrait,
        GenerateTransactionNo,
        ResponseTrait,
        WhereHouseTrait;

    
    protected $bankBeneficiaryRepository;
    protected $bankRepository;
    protected $bankTransferRepository;
    protected $levelRepository;
    protected $serviceTypeRepository;
    protected $transactionRepository;
    protected $userRepository;
    protected $userPolicy;
    protected $walletRepository;
    protected $x_secret_key;

    public function __construct(
        IBankBeneficiaryRepository $bankBeneficiaryRepository,
        IBankRepository $bankRepository,
        IBankTransferRepository $bankTransferRepository,
        ILevelRepository $levelRepository,
        IServiceTypeRepository $serviceTypeRepository, 
        ITransactionRepository $transactionRepository, 
        IUserRepository $userRepository, 
        UserPolicy $userPolicy, 
        IWalletRepository $walletRepository,
    )
    {

        $this->bankBeneficiaryRepository = $bankBeneficiaryRepository;
        $this->bankRepository = $bankRepository;
        $this->bankTransferRepository = $bankTransferRepository;
        $this->levelRepository = $levelRepository;
        $this->serviceTypeRepository = $serviceTypeRepository;
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
        $this->userPolicy = $userPolicy;
        $this->walletRepository = $walletRepository;
        $this->x_secret_key = env('PSG_SECRET_KEY');
    }
    
    public function getUser(string $id){
        
        if($id != Auth::id())
        {
            return $this->errorResponse(
                ['id' => 'Invalid user Id'], 
                'Validation error',
                422
            );
        }

        $user = $this->userRepository->get($id)->firstOrFail();

        return $this->successResponse(
            new UsersResource($user), 
            'Successful'
        );
    }
    
    public function getAllUser(){

        $user = $this->userRepository->getAll();

        if($user->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = UsersResource::collection($user);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function getFee($user){

        $fee = 0.00;
        $transfer = json_decode($user->service->transfer);

        if(!$transfer->is_free)
        {
            $fee = $this->serviceTypeRepository->getFeeByName('Bank Transfer');
        }

        return $fee;

    }
    
    public function getWallet($user){

        $resource = WalletsResource::collection($user->wallets);

        return $this->successResponse($resource, 'Successful');

    }

    public function makeTransfer($user, $request)
    {
        $validated = $request->validated();
        $account_name = $validated['account_name'];
        $account_no = $validated['account_no'];
        $amount = $validated['amount'];
        $fee = $this->getFee($user);
        $total_amount = $fee + $amount;
        $add_beneficiary = $validated['add_beneficiary'] ?? false;
        $full_name = $user->profile->first_name ." ". $user->profile->other_name;
        $narration = $validated['narration'] ?? "From ". $full_name;
        $bank_code = $validated['bank_code'];
        $reference_no = $this->generateReferenceNo();
        $transaction_no = $validated['transaction_no'];
        $transfer = json_decode($user->service->transfer);
        $charges = 10;
        $bank = $this->bankRepository->get($bank_code)->first();

        if(!$bank)
        {
            return $this->errorResponse(
                422,
                "Validation error",
                [
                    'bank_code' => "Invalid Bank Code"
                ], 
            );
        }

        $bank_name = $bank->bank_name;


        if(!$transfer->allow )
        {
            return $this->errorResponse(
                message: 'Transfer not allowed for the user',
            );
        }


        if(($user->level->daily_unused -= $total_amount) < 0 )
        {
            return $this->errorResponse(
                message: 'Daily transfer limit exceeded',
            );
        }


        
        if(($user->level->monthly_unused -= $total_amount) < 0 )
        {
            return $this->errorResponse(
                message: 'Monthly transfer limit exceeded',
            );
        }

        
        $wallet = $user->wallets->where('id', $validated['wallet_id'])->first();

        if(!$wallet )
        {
            return $this->errorResponse(
                422,
                'Validation error',
                [
                    'wallet_id' => "Invalid Wallet Id"
                ], 
            );
        }
        

        if($wallet->is_locked->value )
        {
            return $this->errorResponse(
                message: 'Wallet locked',
            );
        }


        if(($wallet->balance -= $total_amount) < 0)
        {
            return $this->errorResponse(
                message: 'Insufficient balance',
            );
        }


        // Transaction
        $transaction = new Transaction;
        $transaction->id = Str::uuid();
        $transaction->number = $transaction_no;
        $transaction->amount = $amount;
        $transaction->fee = $fee;
        $transaction->profit = $fee - $charges;
        $transaction->discount = 0;
        $transaction->total = $amount + $fee;
        $transaction->service_type = ServiceTypeEnum::BankTransfer->value;
        $transaction->status = TransactionStatusEnum::Processing->value;
        $transaction->remark = "Pending";
        $transaction->reference_no = $reference_no;
        $transaction->user_id = $user->id;


        // Bank Transfer
        $bankTransfer = new BankTransfer;
        $bankTransfer->amount = $amount;
        $bankTransfer->account_no = $account_no;
        $bankTransfer->account_name = $account_name;
        $bankTransfer->bank_name = $bank_name;
        $bankTransfer->bank_code = $bank_code;
        $bankTransfer->narration = $narration;
        $bankTransfer->transaction_id = $transaction->id;
        $bankTransfer->user_id = $user->id;


        $apiData = [
            "data" => json_encode([
                "walletId" => env('PSG_NGN_DEFAULT_WALLET'),
                "accountNo" => $account_no,
                "bankCode" => $bank_code,
                "reference" => $reference_no,
                "amount" => $amount,
                "currency" => "NGN",
                "naration" => $narration
            ]),
            "header" => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Secret-Key: '. $this->x_secret_key
            ],
            "url" => env('BANK_TRANSFER_ENDPOINT')
        ];


        DB::beginTransaction();

        $this->walletRepository->store($wallet);
        $this->levelRepository->store($user->level);
        $this->transactionRepository->store($transaction);
        $this->bankTransferRepository->store($bankTransfer);

        // Beneficiary
        if($add_beneficiary)
        {
            $newBeneficiary = new BankBeneficiary;
            $newBeneficiary->account_no = $account_no;
            $newBeneficiary->name = $account_name;
            $newBeneficiary->bank_code = $bank_code;
            $newBeneficiary->bank_name = $bank_name;
            $newBeneficiary->user_id = $user->id;

            // Check if beneficiary already exist
            $beneficiary = $this->bankBeneficiaryRepository->getByAccountNoAndUserId($account_no, $user->id)->first();

            if(!$beneficiary)
            {
                $this->bankBeneficiaryRepository->store($newBeneficiary);
            }
        }

        // Beneficiary
        if($transfer->is_free)
        {
            $transfer->count -= 1;

            $serviceData = json_encode([
                'allow' => $transfer->allow,
                'count' => $transfer->count,
                'is_free' => ($transfer->count > 0) ? 1 : 0 
            ]);

            $user->service()->update([
                'transfer' => $serviceData
            ]);
        }

        
        $transfer = $this->curlPostApi($apiData);

        if(!$transfer->success)
        {
            return $this->errorResponse(
                message: $transfer->message,
                errors: $transfer->errors,
            );
        }

        DB::commit();

        // Send Notification
        $sendEmail = $this->sendBankTransferEmail($transaction);

        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message,
                errors: $sendEmail->errors,
            );
        }


        return $this->successResponse(
            message: 'Transfer Successful',
            data: new TransfersResource($transaction), 
        );
    }
    
}