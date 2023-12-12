<?php 


namespace App\Implementations\Services;

use App\Enums\EncryptTypeEnum;
use App\Enums\ServiceTypeEnum;
use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Http\Resources\CableBeneficiaryResource;
use App\Http\Resources\CableResource;
use App\Interfaces\Repositories\ICableRepository;
use App\Interfaces\Repositories\ICableBeneficiaryRepository;
use App\Interfaces\Repositories\ILevelRepository;
use App\Interfaces\Repositories\IServiceRepository;
use App\Interfaces\Repositories\ITransactionRepository;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Services\ICableService;
use App\Models\Cable;
use App\Models\CableBeneficiary;
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

class CableService implements ICableService
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
    protected $user;
    protected $user_id;
    protected $verify_endpoint;
    protected $wallet_id;
    protected $x_secret_key;

    protected $cableBeneficiaryRepository;
    protected $cableRepository;
    protected $levelRepository;
    protected $serviceRepository;
    protected $transactionRepository;
    protected $walletRepository;

    public function __construct(
        ICableBeneficiaryRepository $cableBeneficiaryRepository,
        ICableRepository $cableRepository,
        ILevelRepository $levelRepository,
        IServiceRepository $serviceRepository,
        IWalletRepository $walletRepository,
        ITransactionRepository $transactionRepository
    )
    {
        
        $this->verify_endpoint = env('CUSTOMER_VERIFY_ENDPOINT');
        $this->x_secret_key = env('PSG_SECRET_KEY');

        $this->cableRepository = $cableRepository;
        $this->cableBeneficiaryRepository = $cableBeneficiaryRepository;
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

        $beneficiaries = $user->cableBeneficiaries;

        return $this->successResponse(
            data: CableBeneficiaryResource::collection($beneficiaries),
        );
    }
    


    public function list()
    {
        
        /* $dataList = $this->cableRepository->fetchProviders();


        return $this->successResponse(
            data: $dataList,
        ); */
    }
    

    public function getPackageList($request)
    {
        $validated = $request->validated();
        $this->snug_id = $validated['snug_id'];
        
        $type = EncryptTypeEnum::CABLE_PROVIDER_LIST->value;
        $name = "snug_id";

        $provider = $this->checkEncryption($this->snug_id, $type, $name);

        if(!$provider)
        {

            return $this->errorResponse;
        }
        

        
        $response  = $this->cableRepository->fetchPackages($provider);

        if(!$response->success)
        {
            return $this->errorResponse(
                message: $response->errors
            );
        }

        
        $name = "Cable";
        $service = $this->serviceRepository->fetchByName($name)->firstOrFail();
        $charges = $service->charges;
        $discount = $service->discount;
        $fee = $service->fee;

        $packages = $response->data->data;

        foreach($packages as $package)
        {

            
            $package->provider = [
                "name" => $provider->name,
                "commission" => $provider->commission,
                "is_percent" => $provider->is_percent,
            ];
            $package->charges = $charges;
            $package->discount = $discount;
            $package->fee = $fee;
            $package->_type = EncryptTypeEnum::CABLE_PACKAGE_LIST->value;
            $package->package_id = $this->dataEncrypt($package);
        }


        return $this->successResponse(
            data: $packages,
        );

    }
    
    public function getProviderList()
    {

        $response = $this->cableRepository->fetchProviders();

        if(!$response->success)
        {
            return $this->errorResponse(
                message: $response->errors
            );
        }


        $providerList = $response->data->data;
        
        foreach($providerList as $provider)
        {
            
            $provider->_type = EncryptTypeEnum::CABLE_PROVIDER_LIST->value;
            $provider->snug_id = $this->dataEncrypt($provider);
        }


        return $this->successResponse(
            data: $providerList,
        );
    }

    

    public function purchaseCable($request, $user)
    {

        $validated = $request->validated();

        $this->auth = $validated['auth'];
        $this->auth_method = $validated['auth_method'];
        $this->customer_id = $validated['customer_id'];
        $this->customer_name = $validated['customer_name'];
        $this->add_beneficiary = $validated['add_beneficiary'] ?? false;
        $this->package_id = $validated['package_id'];
        $this->reference_no = $this->generateReferenceNo();
        $this->transaction_no = $validated['transaction_no'];
        $this->wallet_id = $validated['wallet_id'];
        $this->user_id = $user->id;
        $this->type = TransactionTypeEnum::Debit->value;
        $this->service_type = ServiceTypeEnum::Cable->value;
        $this->status = TransactionStatusEnum::Processing->value;
        $this->remark = "Pending";

        
        if(!$this->verifyUser($user, $this->auth, $this->auth_method))
        {
            return $this->errorResponse;
        }


        $service = $user->serviceByName('Cable');
        $type = EncryptTypeEnum::CABLE_PACKAGE_LIST->value;
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
        $this->narration = $package->provider->name ." cable tv sub to: ". $this->customer_id;
        
        
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


        if(!$service->is_available )
        {
            return $this->errorResponse(
                message: 'Service not available',
            );
        }


        if(!$service->pivot->is_allow )
        {
            return $this->errorResponse(
                message: 'Cable purchase not allowed for the user',
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



        // Cable
        $cable = new Cable;
        $cable->id = Str::uuid();
        $cable->amount = $this->amount;
        $cable->customer_id = $this->customer_id;
        $cable->customer_name = $this->customer_name;
        $cable->provider_name = $package->provider->name;
        $cable->description = $package->name;
        $cable->transaction_id = $transaction->id;
        $cable->user_id = $this->user_id;


        $fee_narration = "Transaction Fee for ". $this->narration;

        $feeTransaction = $this->createTransactionFee($wallet, $fee_narration, $cable->id);

        if(!$feeTransaction)
        {
            return $this->errorResponse;
        }

        
        $newBeneficiary = new CableBeneficiary;
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
                "reference" => $this->reference_no,
                "_id" => $package->_id,
                "name" => $package->name,
                "code" => $package->code,
                "is_fixed" => $package->is_fixed,
                "provider_id" => $package->provider_id,
                "others" => $package->others
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
        $this->cableRepository->store($cable);

        
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
        /* $sendEmail = $this->sendCablePurchaseSuccessEmail($transaction);

        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message,
                errors: $sendEmail->errors,
            );
        } */


        return $this->successResponse(
            message: 'Successful',
            data: new CableResource($transaction), 
        );
    }


    
    public function updateCable($request, $id)
    {
        $validated = $request->validated();

        $user = $this->cableRepository->fetch($id)->firstOrFail();

        $update = $this->cableRepository->update($user, $validated);

        return $this->successResponse(
            new CableResource($update), 
            'Updated Successful'
        );
    }


    public function verifyCustomer($request)
    {
        $validated = $request->validated();
        $this->customer_id = $validated['customer_id'];
        $this->package_id = $validated['package_id'];

        $type = EncryptTypeEnum::CABLE_PACKAGE_LIST->value;
        $name = "package_id";

        $package = $this->checkEncryption($this->package_id, $type, $name);

        if(!$package)
        {

            return $this->errorResponse;
        }


        $data = [
            "bill_type"=> "tvx",
            "customer_id"=> $this->customer_id,
            "_id"=> $package->_id,
            "name"=> $package->name,
            "code"=> $package->code,
            "amount"=> $package->amount,
            "is_fixed" => $package->is_fixed,
            "provider_id"=> $package->provider_id,
            "others"=> $package->others,
        ];


        $response = $this->cableRepository->fetchCustomer($data);

        if(!$response->success)
        {
            return $this->errorResponse(
                message: $response->errors
            );
        }


        
        return $this->successResponse(
            data: $response->data->data,
        );

    }
    
    public function removeBeneficiary($beneficiary){

        $delete = $this->cableBeneficiaryRepository->delete($beneficiary);

        if(!$delete)
        {
            return $this->errorResponse(
                message: 'Something went wrong, Beneficiary not Deleted|'
            );
        }

        return $this->successResponse(
            message: 'Deleted Successful'
        );
    }
    
    public function deleteCable(string $id){

        $user = $this->cableRepository->fetch($id)->firstOrFail();

        $delete = $this->cableRepository->delete($user);

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

    
    
    protected function getAmount($package, $validated){

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

    
    
    protected function getTotalFee($package){

        return $package->charges +  $package->fee;

    }
    
    
    protected function calculateCommissionAmount($package){

        if($package->provider->is_percent)
        {
            $commission_amount = ($package->provider->commission/100) * $this->amount;
            
        }
        else
        {
            $commission_amount = $package->provider->commission;
        }

        

        return number_format($commission_amount, 2, '.', '');

    }
    
    
    protected function calculateDiscountAmount($package){

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



    protected function storeBeneficiary($newBeneficiary)
    {

        // Beneficiary
        if($this->add_beneficiary)
        {

            // Check if beneficiary already exist
            $beneficiary = $this->cableBeneficiaryRepository->fetchByUserIdCustomerId($this->user_id, $this->customer_id)->first();

            if(!$beneficiary)
            {
                $this->cableBeneficiaryRepository->store($newBeneficiary);
            }
        }
    }

    

    
}