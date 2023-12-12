<?php 


namespace App\Implementations\Services;

use App\Enums\EncryptTypeEnum;
use App\Enums\ServiceTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Http\Resources\ElectricityBeneficiaryResource;
use App\Http\Resources\ElectricityResource;
use App\Interfaces\Repositories\IElectricityRepository;
use App\Interfaces\Repositories\IElectricityBeneficiaryRepository;
use App\Interfaces\Repositories\ILevelRepository;
use App\Interfaces\Repositories\IServiceRepository;
use App\Interfaces\Repositories\ITransactionRepository;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Services\IElectricityService;
use App\Models\Electricity;
use App\Models\ElectricityBeneficiary;
use App\Models\Transaction;
use App\Traits\AuthTrait;
use App\Traits\EmailTrait;
use App\Traits\GenerateTransactionNo;
use App\Traits\EncryptionTrait;
use App\Traits\ResponseTrait;
use App\Traits\ServiceTrait;
use App\Traits\StatusTrait;
use App\Traits\TransactionTrait;
use App\Traits\WalletTrait;
use App\Traits\WhereHouseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ElectricityService implements IElectricityService
{
    use AuthTrait,
        EmailTrait,
        GenerateTransactionNo,
        EncryptionTrait,
        ResponseTrait,
        ServiceTrait,
        StatusTrait,
        TransactionTrait,
        WalletTrait,
        WhereHouseTrait;

    
    
    protected $add_beneficiary;
    protected $amount;
    protected $auth;
    protected $auth_method;
    protected $balance_after;
    protected $balance_before;
    protected $charges;
    protected $commission;
    protected $customer_name;
    protected $customer_id;
    protected $discount;
    protected $errorResponse;
    protected $fee;
    protected $meter_type;
    protected $narration;
    protected $package_id;
    protected $snug_id;
    protected $total_fee;
    protected $reference_no;
    protected $total_amount;
    protected $transaction_no;
    protected $user_id;
    protected $type;
    protected $profit;
    protected $service_type;
    protected $status;
    protected $remark;
    protected $verify_endpoint;
    protected $wallet_id;

    protected $electricityBeneficiaryRepository;
    protected $dataCommission;
    protected $electricityRepository;
    protected $levelRepository;
    protected $serviceRepository;
    protected $transactionRepository;
    protected $walletRepository;
    protected $x_secret_key;

    public function __construct(
        IElectricityBeneficiaryRepository $electricityBeneficiaryRepository,
        IElectricityRepository $electricityRepository,
        ILevelRepository $levelRepository,
        IServiceRepository $serviceRepository,
        IWalletRepository $walletRepository,
        ITransactionRepository $transactionRepository
    )
    {
        
        $this->dataCommission = env('DATA_COMMISSION');
        $this->verify_endpoint = env('CUSTOMER_VERIFY_ENDPOINT');
        $this->x_secret_key = env('PSG_SECRET_KEY');

        $this->electricityRepository = $electricityRepository;
        $this->electricityBeneficiaryRepository = $electricityBeneficiaryRepository;
        $this->levelRepository = $levelRepository;
        $this->serviceRepository = $serviceRepository;
        $this->walletRepository = $walletRepository;
        $this->transactionRepository = $transactionRepository;
    }



    public function deleteBeneficiary($user, $beneficiary)
    {
        $canDelete = $user->can('delete', $beneficiary);

        if(!$canDelete)
        {
            return $this->errorResponse(
                message: "You are not authorized to delete the beneficiary."
            );
        }


        $deleted = $beneficiary->delete();

        if(!$deleted)
        {
            return $this->errorResponse(
                message: "Unable delete the beneficiary, try again."
            );
        }


        return $this->successResponse(
            message: "Deleted successful."
        );

    }



    public function getBeneficiaryByUser($user)
    {
        // $beneficiaries = $this->electricityBeneficiaryRepository->fetchByUserId($user->id)->get();

        $beneficiaries = $user->electricityBeneficiaries()->get();

        return $this->successResponse(
            data: ElectricityBeneficiaryResource::collection($beneficiaries),
        );
    }
    
    public function list()
    {
        
        $dataList = $this->electricityRepository->fetchProviders();


        return $this->successResponse(
            data: $dataList,
        );
    }
    
    public function packageList($request)
    {
        $validated = $request->validated();
        $snug_id = $validated['snug_id'];

        $type = EncryptTypeEnum::ELECTRICITY_PROVIDER_LIST->value;
        $name = "snug_id";
        
        $provider = $this->checkEncryption($snug_id, $type, $name);


        if(!$provider)
        {

            return $this->errorResponse;
        }
        

        $response = $this->electricityRepository->fetchPackages($provider);

        if(!$response->success)
        {

            return $this->errorResponse(
                message: $response->message,
                errors: $response->errors
            );
        }


        
        $name = "Electricity";
        $service = $this->serviceRepository->fetchByName($name)->firstOrFail();
        $charges = $service->charges;
        $discount = $service->discount;
        $fee = $service->fee;

        $packageList = $response->data->data;

        foreach($packageList as $package)
        {
            

            $package->provider = $provider;

            $package->charges = $charges;
            $package->discount = $discount;
            $package->fee = $fee;
            $package->_type = EncryptTypeEnum::ELECTRICITY_PACKAGE_LIST->value;
            $package->package_id = $this->dataEncrypt($package);
        }

        return $this->successResponse(
            data: $packageList,
        );

    }
    
    public function providerList()
    {
        
        $response = $this->electricityRepository->fetchProviders();


        if(!$response->success)
        {
            return $this->errorResponse(
                message: $response->errors
            );
        }


        $providerList = $response->data->data;
        
        foreach($providerList as $provider)
        {
            $provider->_type = EncryptTypeEnum::ELECTRICITY_PROVIDER_LIST->value;
            $provider->snug_id = $this->dataEncrypt($provider);
        }


        return $this->successResponse(
            data: $providerList,
        );
    }

    

    public function purchaseElectricity($request, $user)
    {

        $validated = $request->validated();

        
        $this->auth = $validated['auth'];
        $this->auth_method = $validated['auth_method'];
        $this->amount = $this->getAmount($validated);
        $this->customer_id = $validated['customer_id'];
        $this->customer_name = $validated['customer_name'];
        $this->add_beneficiary = $validated['add_beneficiary'] ?? false;
        $this->package_id = $validated['package_id'];
        $this->reference_no = $this->generateReferenceNo();
        $this->transaction_no = $validated['transaction_no'];
        $this->wallet_id = $validated['wallet_id'];
        $this->user_id = $user->id;
        $this->type = TransactionTypeEnum::Debit->value;
        $this->service_type = ServiceTypeEnum::Electricity->value;
        $this->status = TransactionStatusEnum::Processing->value;
        $this->remark = "Pending";

        if(!$this->verifyUser($user, $this->auth, $this->auth_method))
        {
            return $this->errorResponse;
        }


        $service = $user->serviceByName('Electricity');
        $type = EncryptTypeEnum::ELECTRICITY_PACKAGE_LIST->value;
        $name = "package_id";

        $package = $this->checkEncryption($this->package_id, $type, $name);

        if(!$package)
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

        
        $this->charges = $package->charges;
        $this->fee = $package->fee;
        $this->commission = $this->calculateCommissionAmount($package);
        $this->discount = $this->calculateDiscountAmount($package);
        $this->total_fee = $this->getTotalFee($package);
        $this->profit = $this->calculateTransactionProfit($service);
        $this->narration = $package->provider->name ." Electricity bill payment to ". $this->customer_id;
        
        $user->level->daily_unused -= $this->amount;
        $user->level->monthly_unused -= $this->amount;



        if(!$service || !$service->is_available )
        {
            return $this->errorResponse(
                message: 'Service not available',
            );
        }



        if(!$service->pivot->is_allow )
        {
            return $this->errorResponse(
                message: 'Electricity purchase not allowed for the user',
            );
        }


        if($user->level->daily_unused < 0)
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


        // Electricity
        $electricity = new Electricity;
        $electricity->id = Str::uuid();
        $electricity->amount = $this->amount;
        $electricity->customer_id = $this->customer_id;
        $electricity->customer_name = $this->customer_name;
        $electricity->provider_name = $package->provider->name;
        $electricity->transaction_id = $transaction->id;
        $electricity->user_id = $user->id;
        

        $fee_narration = "Transaction Fee for ". $package->provider->name ." bill payment to: ". $this->customer_id;
        $feeTransaction = $this->createTransactionFee($wallet, $fee_narration, $electricity->id);

        if(!$feeTransaction)
        {
            return $this->errorResponse;
        }


        
        $newBeneficiary = new ElectricityBeneficiary;
        $newBeneficiary->customer_id = $this->customer_id;
        $newBeneficiary->customer_name = $this->customer_name;
        $newBeneficiary->provider_name = $package->provider->name;
        $newBeneficiary->user_id = $user->id;
        

        
        $apiData = [
            "data" => json_encode([
                "process_type" => "batch",
                "wallet_id" => env('PSG_NGN_DEFAULT_WALLET'),
                "customer_id" => $this->customer_id,
                "amount" => $this->amount,
                "meter_type" => $package->code,
                "reference" => $this->reference_no,
                "_id" => $package->provider->_id,
                "name" => $package->provider->name,
                "code" => $package->provider->code,
                "is_percent" => $package->provider->is_percent,
                "provider_id" => $package->provider->provider_id,
                "others" => $package->provider->others
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
        $this->electricityRepository->store($electricity);
        

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
        /* $sendEmail = $this->sendElectricityPurchaseSuccessEmail($transaction);

        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message,
                errors: $sendEmail->errors,
            );
        } */


        return $this->successResponse(
            message: 'Successful',
            data: new ElectricityResource($transaction), 
        );
    }


    
    public function updateElectricity($request, $id)
    {
        $validated = $request->validated();

        $user = $this->electricityRepository->fetch($id)->firstOrFail();

        $update = $this->electricityRepository->update($user, $validated);

        return $this->successResponse(
            new ElectricityResource($update), 
            'Updated Successful'
        );
    }


    public function verifyCustomer($request)
    {
        $validated = $request->validated();
        $this->customer_id = $validated['customer_id'];
        $this->package_id = $validated['package_id'];


        $type = EncryptTypeEnum::ELECTRICITY_PACKAGE_LIST->value;
        $name = "package_id";
        
        $package = $this->checkEncryption($this->package_id, $type, $name);


        if(!$package)
        {
            
            return $this->errorResponse;
        }

        
        $data = [
            "customer_id"=> $this->customer_id,
            "_id"=> $package->provider->_id,
            "name"=> $package->provider->name,
            "code"=> $package->provider->code,
            "meter_type"=> $package->code,
            "is_percent"=> $package->provider->is_percent,
            "provider_id"=> $package->provider->provider_id,
            "others"=> $package->provider->others,
        ];


        $response = $this->electricityRepository->fetchCustomer($data);

        print_r($response);
    }
    
    public function deleteElectricity(string $id){

        $user = $this->electricityRepository->fetch($id)->firstOrFail();

        $delete = $this->electricityRepository->delete($user);

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

    
    
    public function getAmount($validated){

        return $validated['amount'];

    }

    
    
    public function getTotalFee($package){

        return $package->charges +  $package->fee;

    }
    
    
    public function calculateCommissionAmount($package){


        if($package->provider->is_percent)
        {
            $commission = ($package->provider->commission/100) * $this->amount;
        }
        else
        {
            $commission = $package->provider->commission;
        }


        return number_format($commission, 2, '.', '');

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

     
    
    private function storeBeneficiary($newBeneficiary){

        if($this->add_beneficiary)
        {

            // Check if beneficiary already exist
            $beneficiary = $this->electricityBeneficiaryRepository->fetchByUserIdCustomerId($this->user_id, $this->customer_id)->first();

            if(!$beneficiary)
            {
                $this->electricityBeneficiaryRepository->store($newBeneficiary);
            }
        }

    }
}