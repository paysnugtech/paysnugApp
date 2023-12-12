<?php 


namespace App\Implementations\Services;

use App\Enums\EncryptTypeEnum;
use App\Enums\ServiceTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Http\Resources\DataBeneficiaryResource;
use App\Http\Resources\DataResource;
use App\Interfaces\Repositories\IDataRepository;
use App\Interfaces\Repositories\IDataBeneficiaryRepository;
use App\Interfaces\Repositories\ILevelRepository;
use App\Interfaces\Repositories\IServiceRepository;
use App\Interfaces\Repositories\IServiceTypeRepository;
use App\Interfaces\Repositories\ITransactionRepository;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Services\IDataService;
use App\Models\Data;
use App\Models\DataBeneficiary;
use App\Models\Transaction;
use App\Traits\AuthTrait;
use App\Traits\EmailTrait;
use App\Traits\GenerateTransactionNo;
use App\Traits\EncryptionTrait;
use App\Traits\ResponseTrait;
use App\Traits\TransactionTrait;
use App\Traits\ServiceTrait;
use App\Traits\StatusTrait;
use App\Traits\WalletTrait;
use App\Traits\WhereHouseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataService implements IDataService
{
    use AuthTrait,
        EmailTrait,
        GenerateTransactionNo,
        EncryptionTrait,
        ResponseTrait,
        TransactionTrait,
        ServiceTrait,
        StatusTrait,
        WalletTrait,
        WhereHouseTrait;

        
    protected $add_beneficiary;
    protected $amount;
    protected $amount_paid;
    protected $auth;
    protected $auth_method;
    protected $balance_before;
    protected $balance_after;
    protected $charges;
    protected $commission;
    protected $customer_id;
    protected $discount;
    protected $fee;
    protected $narration;
    protected $package_id;
    protected $profit;
    protected $reference_no;
    protected $remark;
    protected $service_type;
    protected $snug_id;
    protected $status;
    protected $total_fee;
    protected $transaction_no;
    protected $type;
    protected $user_id;
    protected $wallet_id;


    protected $dataBeneficiaryRepository;
    protected $dataRepository;
    protected $walletRepository;
    protected $levelRepository;
    protected $serviceRepository;
    protected $serviceTypeRepository;
    protected $transactionRepository;
    protected $x_secret_key;

    public function __construct(
        IDataRepository $dataRepository,
        IDataBeneficiaryRepository $dataBeneficiaryRepository,
        ILevelRepository $levelRepository,
        IServiceRepository $serviceRepository,
        IServiceTypeRepository $serviceTypeRepository,
        ITransactionRepository $transactionRepository,
        IWalletRepository $walletRepository,
    )
    {

        $this->x_secret_key = env('PSG_SECRET_KEY');
        $this->dataRepository = $dataRepository;
        $this->dataBeneficiaryRepository = $dataBeneficiaryRepository;
        $this->levelRepository = $levelRepository;
        $this->serviceRepository = $serviceRepository;
        $this->serviceTypeRepository = $serviceTypeRepository;
        $this->transactionRepository = $transactionRepository;
        $this->walletRepository = $walletRepository;
    }


    public function deleteBeneficiary($beneficiary)
    {

        $delete = $this->dataBeneficiaryRepository->delete($beneficiary);

        if(!$delete)
        {
            
            return $this->errorResponse(
                message: 'Unable to delete beneficiary.',
            );
        }

        return $this->successResponse(
            message: "Deleted successful."
        );
    }


    public function getBeneficiaryByUser($user)
    {
        $beneficiaries = $user->dataBeneficiaries;

        return $this->successResponse(
            data: DataBeneficiaryResource::collection($beneficiaries),
        );
    }

    
    public function list()
    {
        
        $dataList = $this->dataRepository->fetchProviders();


        return $this->successResponse(
            data: $dataList,
        );
    }
    
    public function packageList($request)
    {
        $validated = $request->validated();
        $this->snug_id = $validated['snug_id'];

        $type = EncryptTypeEnum::DATA_PROVIDER_LIST->value;
        $name = "snug_id";
        
        $provider = $this->checkEncryption($this->snug_id, $type, $name);

        if(!$provider)
        {

            return $this->errorResponse;
        }

        $response = $this->dataRepository->fetchPackages($provider);

        if(!$response->success)
        {

            return $this->errorResponse(
                message: $response->message,
                errors: $response->errors
            );
        }

        $name = "Data";
        $service = $this->serviceRepository->fetchByName($name)->firstOrFail();
        $charges = $service->charges;
        $discount = $service->discount;
        $fee = $service->fee;
        $packageList = $response->data->data;

        foreach($packageList as $package)
        {

            $package->provider = [
                "name" => $provider->name,
                "commission" => $provider->commission,
                "is_percent" => $provider->is_percent,
            ];

            $package->charges = $charges;
            $package->discount = $discount;
            $package->fee = $fee;
            $package->_type = EncryptTypeEnum::DATA_PACKAGE_LIST->value;
            $package->package_id = $this->dataEncrypt($package);
        }

        return $this->successResponse(
            data: $packageList,
        );

    }
    
    public function providerList()
    {
        $name = "Data";
        $discount = $this->getDiscountByName($name);
        $charges = $this->getChargesByName($name);
        $fee = $this->getFeeByName($name);
        

        $response = $this->dataRepository->fetchProviders();


        if(!$response->success)
        {
            return $this->errorResponse(
                code: $response->code,
                message: $response->message,
                errors: $response->errors
            );
        }


        $providerList = $response->data->data;
        
        foreach($providerList as $provider)
        {
            // $commission = $provider->commission;

            
            $provider->_type = EncryptTypeEnum::DATA_PROVIDER_LIST->value;
            $provider->snug_id = $this->dataEncrypt($provider);
        }


        return $this->successResponse(
            data: $providerList,
        );

    }

    

    public function purchaseData($request, $user)
    {

        $validated = $request->validated();

        $this->auth = $validated['auth'];
        $this->auth_method = $validated['auth_method'];
        $this->customer_id = $validated['customer_id'];
        $this->add_beneficiary = $validated['add_beneficiary'] ?? false;
        $this->package_id = $validated['package_id'];
        $this->reference_no = $this->generateReferenceNo();
        $this->transaction_no = $validated['transaction_no'];
        $this->wallet_id = $validated['wallet_id'];
        $this->user_id = $user->id;
        $this->type = TransactionTypeEnum::Debit->value;
        $this->service_type = ServiceTypeEnum::Data->value;
        $this->status = TransactionStatusEnum::Processing->value;
        $this->remark = "Pending";

        if(!$this->verifyUser($user, $this->auth, $this->auth_method))
        {
            return $this->errorResponse;
        }


        $service = $user->serviceByName('Data');
        $type = EncryptTypeEnum::DATA_PACKAGE_LIST->value;
        $name = "package_id";

        $package = $this->checkEncryption($this->package_id, $type, $name);

        if(!$package)
        {

            return $this->errorResponse;
        }


        
        $this->charges = $package->charges;
        $this->fee = $package->fee;
        $this->amount = $this->getAmount($package, $validated);
        $this->commission = $this->calculateCommissionAmount($package);
        $this->discount = $this->calculateDiscountAmount($package);
        $this->total_fee = $this->getTotalFee($package);
        $this->profit = $this->calculateTransactionProfit($service);
        $this->narration = $package->provider->name ." data to: ". $this->customer_id;

        
        $user->level->daily_unused -= $this->amount;
        $user->level->monthly_unused -= $this->amount;

    

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





        if(!$service || !$service->is_available)
        {
            return $this->errorResponse(
                message: 'Service not available',
            );
        }



        if(!$service->pivot->is_allow )
        {
            return $this->errorResponse(
                message: 'Data purchase not allowed for the user',
            );
        }


        if($user->level->daily_unused < 0 )
        {
            return $this->errorResponse(
                message: 'Daily transaction limit exceeded',
            );
        }


        
        if($user->level->monthly_unused < 0 )
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



        // Data
        $data = new Data;
        $data->id = Str::uuid();
        $data->amount = $this->amount;
        $data->customer_id = $this->customer_id;
        $data->provider_name = $package->provider->name;
        $data->description = $package->name;
        $data->transaction_id = $transaction->id;
        $data->user_id = $this->user_id;

        
        
        $fee_narration = "Transaction Fee for ". $package->name;

        $feeTransaction = $this->createTransactionFee($wallet, $fee_narration, $data->id);

        if(!$feeTransaction)
        {
            return $this->errorResponse;
        }


        $newBeneficiary = new DataBeneficiary;
        $newBeneficiary->customer_id = $this->customer_id;
        $newBeneficiary->provider_name = $package->provider->name;
        $newBeneficiary->user_id = $this->user_id;
        
       
        $apiData = [
            "data" => json_encode([
                "wallet_id" => env('PSG_NGN_DEFAULT_WALLET'),
                "process_type" => "batch",
                "customer_id" => $this->customer_id,
                "amount" => $this->amount,
                "reference" => $this->reference_no,
                "_id" => $package->_id,
                "name" => $package->name,
                "code" => $package->code,
                "is_fixed" => $package->is_fixed,
                "provider_id" => $package->provider_id,
                "others" => $package->others,
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
        $this->dataRepository->store($data);
        

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
        /* $sendEmail = $this->sendDataPurchaseSuccessEmail($transaction);

        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message,
                errors: $sendEmail->errors,
            );
        } */


        return $this->successResponse(
            message: 'Successful',
            data: new DataResource($transaction), 
        );
    }


    
    public function updateData($request, $id)
    {
        $validated = $request->validated();

        $user = $this->dataRepository->fetch($id)->firstOrFail();

        $update = $this->dataRepository->update($user, $validated);

        return $this->successResponse(
            new DataResource($update), 
            'Updated Successful'
        );
    }
  
    
    public function deleteData(string $id){

        $user = $this->dataRepository->fetch($id)->firstOrFail();

        $delete = $this->dataRepository->delete($user);

        if(!$delete)
        {
            return $this->errorResponse(
                [], 
                'Something went wrong, User not Deleted|'
            );
        }

        return $this->successResponse(
            'Deleted Successful'
        );
    }

    
    
    public function getAmount($package, $validated){

        if($package->is_fixed)
        {
            $amount = $package->amount;
        }
        else
        {
            $amount = $validated['amount'];
        }

        return $amount;

    }

    
    
    public function getTotalFee($package){

        return $package->charges +  $package->fee;

    }
    
    
    public function calculateCommissionAmount($package){

        if(!$package->provider->is_percent)
        {
           
            $commission_amount = ($package->provider->commission/100) * $this->amount;
        }
        else
        {
            $commission_amount = $package->provider->commission;
        }

        return number_format($commission_amount, 2, '.', '');

    }
    
    
    public function calculateDiscountAmount($package){

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

     
    
    private function storeBeneficiary($newBeneficiary){

        if($this->add_beneficiary)
        {

            // Check if beneficiary already exist
            $beneficiary = $this->dataBeneficiaryRepository->fetchByUserIdCustomerId($this->user_id, $this->customer_id)->first();

            if(!$beneficiary)
            {
                $this->dataBeneficiaryRepository->store($newBeneficiary);
            }
        }

    }
    
}