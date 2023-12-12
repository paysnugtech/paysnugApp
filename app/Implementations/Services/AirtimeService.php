<?php 


namespace App\Implementations\Services;

use App\Enums\EncryptTypeEnum;
use App\Enums\ServiceTypeEnum;
use App\Enums\TransactionTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Http\Resources\AirtimeBeneficiaryResource;
use App\Http\Resources\AirtimesResource;
use App\Interfaces\Repositories\IAirtimeRepository;
use App\Interfaces\Repositories\IAirtimeBeneficiaryRepository;
use App\Interfaces\Repositories\ILevelRepository;
use App\Interfaces\Repositories\IServiceRepository;
use App\Interfaces\Repositories\ITransactionRepository;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Services\IAirtimeService;
use App\Models\Airtime;
use App\Models\AirtimeBeneficiary;
use App\Models\Transaction;
use App\Traits\AuthTrait;
use App\Traits\EmailTrait;
use App\Traits\GenerateTransactionNo;
use App\Traits\EncryptionTrait;
use App\Traits\ResponseTrait;
use App\Traits\StatusTrait;
use App\Traits\TransactionTrait;
use App\Traits\WalletTrait;
use App\Traits\WhereHouseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AirtimeService implements IAirtimeService
{
    use AuthTrait,
        EmailTrait,
        GenerateTransactionNo,
        EncryptionTrait,
        ResponseTrait,
        StatusTrait,
        TransactionTrait,
        WalletTrait,
        WhereHouseTrait;

    
    protected $amount;
    protected $add_beneficiary;
    protected $auth;
    protected $auth_method;
    protected $balance_after;
    protected $balance_before;
    protected $charges;
    protected $commission;
    protected $customer_id;
    protected $discount;
    protected $errorResponse;
    protected $fee;
    protected $narration;
    protected $profit;
    protected $reference_no;
    protected $total_fee;
    protected $transaction_no;
    protected $snug_id;
    protected $wallet_id;
    protected $user_id;
    

    protected $type;
    protected $service_type;
    protected $status;
    protected $remark;

    
    protected $airtimeBeneficiaryRepository;
    protected $airtimeCommission;
    protected $airtimeRepository;
    protected $levelRepository;
    protected $serviceRepository;
    protected $transactionRepository;
    protected $walletRepository;
    protected $x_secret_key;

    public function __construct(
        IAirtimeRepository $airtimeRepository,
        IAirtimeBeneficiaryRepository $airtimeBeneficiaryRepository,
        ILevelRepository $levelRepository,
        IServiceRepository $serviceRepository,
        ITransactionRepository $transactionRepository,
        IWalletRepository $walletRepository)
    {
        
        $this->x_secret_key = env('PSG_SECRET_KEY');
        $this->airtimeRepository = $airtimeRepository;
        $this->airtimeBeneficiaryRepository = $airtimeBeneficiaryRepository;
        $this->levelRepository = $levelRepository;
        $this->serviceRepository = $serviceRepository;
        $this->walletRepository = $walletRepository;
        $this->transactionRepository = $transactionRepository;
    }


    
    
    public function deleteAirtime(string $id){

        $user = $this->airtimeRepository->get($id)->firstOrFail();

        $delete = $this->airtimeRepository->delete($user);

        if(!$delete)
        {
            return $this->errorResponse(
                [], 
                'Something went wrong, User not Deleted|'
            );
        }

        return $this->successResponse(
            [], 
            'Deleted Successful'
        );
    }



    public function getBeneficiaryByUser($user)
    {

        $beneficiaries = $user->airtimeBeneficiaries;

        return $this->successResponse(
            data: AirtimeBeneficiaryResource::collection($beneficiaries)
        );
    }


    
    public function getProviderList()
    {
        
        $response = $this->airtimeRepository->getProviders();


        if(!$response->success)
        {
            
            return $this->errorResponse(
                message: $response->errors
            );
        }

        $name = "Airtime";
        $service = $this->serviceRepository->fetchByName($name)->firstOrFail();
        $charges = $service->charges;
        $discount = $service->discount;
        $fee = $service->fee;

        $providers = $response->data->data;

        foreach($providers as $provider)
        {
            $provider->charges = number_format($charges,2,'.','');
            $provider->discount = $discount;
            $provider->fee = number_format($fee,2,'.','');
            $provider->_type = EncryptTypeEnum::AIRTIME_PROVIDER_LIST->value;
            $provider->snug_id = $this->dataEncrypt($provider);
        }

        return $this->successResponse(
            data: $providers,
        );
    }


    
    public function list()
    {
        
        $airtimeList = $this->airtimeRepository->getAll();


        return $this->successResponse(
            data: $airtimeList,
        );
    }

    

    public function purchaseAirtime($request, $user)
    {
        
        $validated = $request->validated();

        $this->auth = $validated['auth'];
        $this->auth_method = $validated['auth_method'];
        $this->customer_id = $validated['customer_id'];
        $this->amount = $validated['amount'];
        $this->add_beneficiary = $validated['add_beneficiary'] ?? false;
        $this->reference_no = $this->generateReferenceNo();
        $this->snug_id = $validated['snug_id'];
        $this->transaction_no = $validated['transaction_no'];
        $this->wallet_id = $validated['wallet_id'];
        $this->user_id = $user->id;
        $this->type = TransactionTypeEnum::Debit->value;
        $this->service_type = ServiceTypeEnum::Airtime->value;
        $this->status = TransactionStatusEnum::Processing->value;
        $this->remark = "Pending";


        if(!$this->verifyUser($user, $this->auth, $this->auth_method))
        {
            return $this->errorResponse;
        }


        $service = $user->serviceByName('Airtime');
        $type = EncryptTypeEnum::AIRTIME_PROVIDER_LIST->value;
        $name = "snug_id";

        $provider = $this->checkEncryption($this->snug_id, $type, $name);

        if(!$provider)
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

        
        $this->charges = $provider->charges;
        $this->fee = $provider->fee;
        $this->commission = $this->calculateCommissionAmount($provider);
        $this->discount = $this->calculateDiscountAmount($provider);
        $this->total_fee = $this->getTotalFee($provider);
        $this->profit = $this->calculateProfitAmount($service);
        $this->narration = $provider->name ." Airtime to ". $this->customer_id;
        $user->level->daily_unused -= $this->amount;
        $user->level->monthly_unused -= $this->amount;


        if(!$service->is_available )
        {
            return $this->errorResponse(
                message: 'Service not available',
            );
        }


        if(!$service->pivot->is_allow)
        {
            return $this->errorResponse(
                message: 'Airtime purchase not allowed for the user',
            );
        }


        if($user->level->daily_unused < 0 )
        {
            return $this->errorResponse(
                message: 'Daily transaction limit exceeded',
            );
        }

        
        if($user->level->monthly_unused < 0)
        {
            return $this->errorResponse(
                message: 'Monthly transaction limit exceeded',
            );
        }

        
        
        $wallet = $user->wallet($this->wallet_id);

        // Transaction
        $transaction = $this->createTransaction($wallet);

        if(!$transaction)
        {
            return $this->errorResponse;
        }



        // Airtime
        $airtime = new Airtime;
        $airtime->amount = $this->amount;
        $airtime->customer_id = $this->customer_id;
        $airtime->provider_name = $provider->name;
        $airtime->transaction_id = $transaction->id;
        $airtime->user_id = $this->user_id;
        

    
        $fee_narration = "Transaction Fee for ". $provider->name ." Airtime to ". $this->customer_id;
        $feeTransaction = $this->createTransactionFee($wallet, $fee_narration, $airtime->id);

        if(!$feeTransaction)
        {
            return $this->errorResponse;
        }


        // Create Beneficiary
        $newBeneficiary = new AirtimeBeneficiary;
        $newBeneficiary->customer_id = $this->customer_id;
        $newBeneficiary->provider_name = $provider->name;
        $newBeneficiary->user_id = $this->user_id;


        $apiData = [
            "data" => json_encode([
                "process_type" => "batch",
                "wallet_id" => env('PSG_NGN_DEFAULT_WALLET'),
                "customer_id" => $this->customer_id,
                "amount" => $this->amount,
                "reference" => $this->reference_no,
                "_id" => $provider->_id,
                "name" => $provider->name,
                "code" => $provider->code,
                "provider_id" => $provider->provider_id,
                "others" => $provider->others
            ]),
            "header" => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Secret-Key: '. $this->x_secret_key
            ],
            "url" => env('PAY_BILL_ENDPOINT')
        ];



        DB::beginTransaction();

        $this->walletRepository->store($wallet);
        $this->levelRepository->store($user->level);
        $this->transactionRepository->store($transaction);
        $this->airtimeRepository->store($airtime);

        // Beneficiary
        $this->storeBeneficiary($newBeneficiary);

        // transfer Free
        $this->storeTransactionFee($service, $feeTransaction);

        
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
        /* $sendEmail = $this->sendAirtimePurchaseSuccessEmail($transaction);

        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message,
                errors: $sendEmail->errors,
            );
        } */


        return $this->successResponse(
            message: 'Successful',
            data: new AirtimesResource($transaction), 
        );
    }



    public function removeBeneficiary($beneficiary, $user)
    {

        $canDelete = $user->can("delete", $beneficiary);

        if(!$canDelete)
        {
            return $this->errorResponse(
                message: "You are not authorize to remove the beneficiary"
            );
        }


        $deleted = $beneficiary->delete();

        if(!$canDelete)
        {
            return $this->errorResponse(
                message: "Unable to remove the beneficiary, try again"
            );
        }


        return $this->successResponse(
            message: "Deleted successful"
        );
    }


    
    public function updateAirtime($request, $id)
    {
        $validated = $request->validated();

        $user = $this->airtimeRepository->get($id)->firstOrFail();

        $update = $this->airtimeRepository->update($user, $validated);

        return $this->successResponse(
            new AirtimesResource($update), 
            'Updated Successful'
        );
    }

    
    
    
    

    
    
    public function getTotalFee($package){

        return $package->charges +  $package->fee;

    }
    
    
    public function calculateCommissionAmount($package){


        if($package->is_percent)
        {
            $commission_amount = ($package->commission/100) * $this->amount;
        }
        else
        {
            $commission_amount = $package->commission;
        }


        return number_format($commission_amount, 2, '.', '');

    }
    
    
    public function calculateDiscountAmount($package){

        // percent 
        if(Str::contains($package->discount, "%"))
        {
            $percent = Str::remove("%", $package->discount);
            $discount_amount = ($percent/100) * $this->commission;
        }
        else
        {
            $discount_amount = $package->discount;
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
            $beneficiary = $this->airtimeBeneficiaryRepository->fetchByUserIdCustomerId($this->user_id, $this->customer_id)->first();

            if(!$beneficiary)
            {
                $this->airtimeBeneficiaryRepository->store($newBeneficiary);
            }
        }

    }
    
}