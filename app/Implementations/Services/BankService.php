<?php 


namespace App\Implementations\Services;


use App\Enums\EncryptTypeEnum;
use App\Enums\ServiceTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Http\Resources\AccountsResource;
use App\Http\Resources\BankBeneficiaryResource;
use App\Http\Resources\BanksResource;
use App\Http\Resources\TransfersResource;
use App\Interfaces\Repositories\IBankRepository;
use App\Interfaces\Repositories\IBankBeneficiaryRepository;
use App\Interfaces\Repositories\IBankTransferRepository;
use App\Interfaces\Repositories\ILevelRepository;
use App\Interfaces\Repositories\IServiceRepository;
use App\Interfaces\Repositories\ITransactionRepository;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Services\IBankService;
use App\Models\BankBeneficiary;
use App\Models\BankTransfer;
use App\Models\Transaction;
use App\Traits\AuthTrait;
use App\Traits\EncryptionTrait;
use App\Traits\GenerateTransactionNo;
use App\Traits\ResponseTrait;
use App\Traits\StatusTrait;
use App\Traits\TransactionTrait;
use App\Traits\WalletTrait;
use App\Traits\WhereHouseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class BankService implements IBankService
{
    use AuthTrait, 
        EncryptionTrait, 
        GenerateTransactionNo, 
        ResponseTrait, 
        StatusTrait, 
        TransactionTrait, 
        WalletTrait, 
        WhereHouseTrait;


    
    protected $account_name;
    protected $account_no;
    protected $add_beneficiary;
    protected $amount;
    protected $auth;
    protected $auth_method;
    protected $balance_before;
    protected $bank_code;
    protected $bank_id;
    protected $bank_name;
    protected $charges;
    protected $commission;
    protected $discount;
    protected $fee;
    protected $fee_narration;
    protected $full_name;
    protected $narration;
    protected $profit;
    protected $reference_no;
    protected $total_fee;
    protected $transaction_no;
    protected $transfer;
    protected $user_id;
    protected $wallet_id;


    protected $type;
    protected $service_type;
    protected $status;
    protected $remark;


    protected $bankBeneficiaryRepository;
    protected $bankRepository;
    protected $bankTransferRepository;
    protected $levelRepository;
    protected $serviceRepository;
    protected $transactionRepository;
    protected $walletRepository;
    protected $x_secret_key;

    public function __construct(
        IBankBeneficiaryRepository $bankBeneficiaryRepository,
        IBankRepository $bankRepository,
        IBankTransferRepository $bankTransferRepository,
        ILevelRepository $levelRepository,
        IServiceRepository $serviceRepository,
        ITransactionRepository $transactionRepository,
        IWalletRepository $walletRepository
    )
    {
        $this->x_secret_key = env('PSG_SECRET_KEY');
        
        $this->bankBeneficiaryRepository = $bankBeneficiaryRepository;
        $this->bankRepository = $bankRepository;
        $this->bankTransferRepository = $bankTransferRepository;
        $this->levelRepository = $levelRepository;
        $this->serviceRepository = $serviceRepository;
        $this->transactionRepository = $transactionRepository;
        $this->walletRepository = $walletRepository;
    }
    

    public function getAllBank(){

        $allBank = $this->bankRepository->fetchAllBank();

        if(!$allBank->success )
        {
            return $this->errorResponse(
                message: $allBank->message
            );
        }


        $banks = $allBank->data->banks;

        foreach($banks as $bank)
        {
            $bank->_type = EncryptTypeEnum::BANK_TRANSFER_PROVIDER->value;
            $bank->bank_id = $this->dataEncrypt($bank);;
        }


        $resource = BanksResource::collection($banks);

        return $this->successResponse(
            data: $banks, 
            message: 'Successful'
        );

    }

    
    public function getBank(string $bank_code){
        
        $banks = $this->bankRepository->fetchAllBank();

        if(!$banks->success )
        {
            return $this->errorResponse(
                [], 
                $banks->message
            );
        }

        $banks = collect($banks->data);

        $bank = $banks->where('bank_code', $bank_code)->first();

        if(!$bank)
        {
            
            return $this->errorResponse(
                [], 
                "Bank not found!"
            );
        }

        return $this->successResponse(
            new BanksResource($bank), 
            'Successful'
        );
    }
    
    
    public function getAccountDetails($request){

        $validated = $request->validated();

        $apiData = [
            'accountNo' => $validated['account_no'],
            'bankCode' => $validated['bank_code'],
        ];

        $response = $this->bankRepository->accountEnquiry($apiData);


        if(!$response->success )
        {
            return $this->errorResponse(
                $response->code,
                message: $response->message,
                errors: $response->errors
            );
        }

        // print_r($response);

        return $this->successResponse(
            message: 'Successful',
            data: new AccountsResource($response->data->data), 
        );
    }
    

    public function getBankByName($request){

        $validated = $request->validated();

        $bank_name = Str::upper($validated['bank_name']);

        $banks = $this->bankRepository->fetchAllBank();

        if(!$banks->success )
        {
            return $this->errorResponse(
                [], 
                $banks->message
            );
        }

        $banks = collect($banks->data);

        $bank = $banks->where('bank_name', $bank_name)->first();

        if(!$bank)
        {
            
            return $this->errorResponse(
                [], 
                "Bank not found!"
            );
        }

        return $this->successResponse(
            new BanksResource($bank), 
            'Successful'
        );
    }



    public function getBeneficiaryByUser($user)
    {
        
        $beneficiaries = $user->bankBeneficiaries;

        return $this->successResponse(
            data: BankBeneficiaryResource::collection($beneficiaries)
        );
    }

    


    public function makeTransfer($user, $request)
    {
        $validated = $request->validated();

        $this->auth = $validated['auth'];
        $this->auth_method = $validated['auth_method'];
        $this->account_name = $validated['account_name'];
        $this->account_no = $validated['account_no'];
        $this->amount = $validated['amount'];
        $this->bank_id = $validated['bank_id'];
        $this->transaction_no = $validated['transaction_no'];
        $this->wallet_id = $validated['wallet_id'];
        $this->add_beneficiary = $validated['add_beneficiary'] ?? false;
        $this->full_name = $user->profile->fullName();
        $this->narration = $validated['narration'] ?? "From ". $this->full_name;
        $this->reference_no = $this->generateReferenceNo();
        $this->user_id = $user->id;
        $this->type = TransactionTypeEnum::Debit->value;
        $this->service_type = ServiceTypeEnum::BankTransfer->value;
        $this->status = TransactionStatusEnum::Processing->value;
        $this->remark = "Pending";


        if(!$this->verifyUser($user, $this->auth, $this->auth_method))
        {
            return $this->errorResponse;
        }
        
        $service = $user->serviceByName('Bank Transfer');
        $type = EncryptTypeEnum::BANK_TRANSFER_PROVIDER->value;
        $name = "bank_id";

        $bank = $this->checkEncryption($this->bank_id, $type, $name);

        if(!$bank)
        {

            return $this->errorResponse;
        }



        $duplicate = $this->checkDuplicateTransaction(
            $service, 
            $this->user_id, 
            $this->service_type, 
            $this->amount
        );


        if($duplicate)
        {
            return $this->errorResponse;
        }

        
        $this->bank_code = $bank->bank_code;
        $this->bank_name = $bank->bank_name;
        $this->charges = $service->charges;
        $this->fee = $service->fee;
        $this->commission = $this->calculateCommissionAmount($service);
        $this->discount = $this->calculateDiscountAmount($service);
        $this->total_fee = $this->getTotalFee($service);
        $this->profit = $this->calculateProfitAmount($service);

        $user->level->daily_unused -= $this->amount;
        $user->level->monthly_unused -= $this->amount;
        

        if(!$service->is_available )
        {
            return $this->errorResponse(
                message: 'Service not available',
            );
        }


        if(!$service->pivot->is_allow )
        {
            return $this->errorResponse(
                message: 'Transfer not allowed for the user',
            );
        }



        if($user->level->daily_unused < 0)
        {
            return $this->errorResponse(
                message: 'Daily transfer limit exceeded',
            );
        }


        
        if($user->level->monthly_unused < 0)
        {
            return $this->errorResponse(
                message: 'Monthly transfer limit exceeded',
            );
        }

    
        

        $wallet = $user->wallet($this->wallet_id);

        // Transaction
        $transaction = $this->createTransaction($wallet);

        if(!$transaction)
        {
            return $this->errorResponse;
        }



        // Bank Transfer
        $bankTransfer = new BankTransfer;
        $bankTransfer->id = Str::uuid();
        $bankTransfer->amount = $this->amount;
        $bankTransfer->account_no = $this->account_no;
        $bankTransfer->account_name = $this->account_name;
        $bankTransfer->bank_name = $this->bank_name;
        $bankTransfer->bank_code = $this->bank_code;
        $bankTransfer->narration = $this->narration;
        $bankTransfer->transaction_id = $transaction->id;
        $bankTransfer->user_id = $this->user_id;



        // BankBeneficiary
        $newBeneficiary = new BankBeneficiary;
        $newBeneficiary->account_no = $this->account_no;
        $newBeneficiary->account_name = $this->account_name;
        $newBeneficiary->bank_code = $this->bank_code;
        $newBeneficiary->bank_name = $this->bank_name;
        $newBeneficiary->user_id = $this->user_id;



        $fee_narration = "Transaction Fee for Transfer to ". $this->account_name ." with account no: ". $this->account_no;
        $feeTransaction = $this->createTransactionFee($wallet, $fee_narration, $bankTransfer->id);

        if(!$feeTransaction)
        {
            return $this->errorResponse;
        }


        $apiData = [
            "data" => json_encode([
                "walletId" => env('PSG_NGN_DEFAULT_WALLET'),
                "accountNo" => $this->account_no,
                "bankCode" => $this->bank_code,
                "reference" => $this->reference_no,
                "amount" => $this->amount,
                "currency" => "NGN",
                "naration" => $this->narration
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
        $this->storeBeneficiary($newBeneficiary);

        // transfer Free
        $this->storeTransactionFee($service, $feeTransaction);



        
        /* $transfer = $this->curlPostApi($apiData);

        if(!$transfer->success)
        {
            return $this->errorResponse(
                message: $transfer->message,
                errors: $transfer->errors,
            );
        } */

        DB::commit();


        /* print_r($transaction);
        print_r($service->pivot);
        dd(); */

        // Send Notification
        /* $sendEmail = $this->sendBankTransferEmail($transaction);

        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message,
                errors: $sendEmail->errors,
            );
        } */


        return $this->successResponse(
            message: 'Transfer Successful',
            data: new TransfersResource($transaction), 
        );
    }

    
    
    public function getTotalFee($service){

        return $service->charges +  $service->fee;

    }
    
    
    public function calculateCommissionAmount($service){


        /* if($package->is_percent)
        {
            $commission_amount = ($package->commission/100) * $this->amount;
        }
        else
        {
            $commission_amount = $package->commission;
        } */

        $commission_amount = 0;


        return number_format($commission_amount, 2, '.', '');

    }
    
    
    public function calculateDiscountAmount($service){

        // percent 
        if(Str::contains($service->discount, "%"))
        {
            $percent = Str::remove("%", $service->discount);
            $discount_amount = ($percent/100) * $this->fee;
        }
        else
        {
            $discount_amount = $service->discount;
        }

        
        return number_format($discount_amount, 2, '.', '');

    }
    
    
    public function calculateProfitAmount($service){

        if($service->pivot->is_free)
        {
            
            $profit = $this->fee +  $this->commission - $this->discount - $this->total_fee;
        }
        else
        {
            $profit = $this->fee +  $this->commission  - $this->discount;
        }

        
        return number_format($profit, 2, '.', '');

    }
    
    
    private function storeBeneficiary($newBeneficiary){

        if($this->add_beneficiary)
        {

            // Check if beneficiary already exist
            $beneficiary = $this->bankBeneficiaryRepository->getByAccountNoAndUserId($this->account_no, $this->user_id)->first();

            if(!$beneficiary)
            {
                $this->bankBeneficiaryRepository->store($newBeneficiary);
            }
        }

    }

}