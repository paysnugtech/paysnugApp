<?php 


namespace App\Implementations\Services;

use App\Interfaces\Repositories\ICableBeneficiaryRepository;
use App\Interfaces\Services\ICableBeneficiaryService;
use App\Traits\EmailTrait;
use App\Traits\GenerateTransactionNo;
use App\Traits\HashTrait;
use App\Traits\ResponseTrait;
use App\Traits\WhereHouseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CableBeneficiaryService implements ICableBeneficiaryService
{
    use EmailTrait,
        GenerateTransactionNo,
        HashTrait,
        ResponseTrait,
        WhereHouseTrait;

    
    


    protected $cableBeneficiaryRepository;

    public function __construct(
        ICableBeneficiaryRepository $cableBeneficiaryRepository
    )
    {

        $this->cableBeneficiaryRepository = $cableBeneficiaryRepository;
    }
    
    public function list()
    {
        
        $beneficiaries = $this->cableBeneficiaryRepository->fetchAll();


        return $this->successResponse(
            data: $beneficiaries,
        );
    }
    
    public function getBeneficiary($id)
    {

        $beneficiaries = $this->cableBeneficiaryRepository->fetch($id);


        return $this->successResponse(
            data: $beneficiaries,
        );

    }

    
    public function getBeneficiaryByCustomerId($customer_id)
    {

        $beneficiaries = $this->cableBeneficiaryRepository->fetchByCustomerId($customer_id);


        return $this->successResponse(
            data: $beneficiaries,
        );

    }

    
    public function getBeneficiaryByUserId($user_id)
    {
        

        $beneficiaries = $this->cableBeneficiaryRepository->fetchByUserId($user_id);


        return $this->successResponse(
            data: $beneficiaries,
        );

    }


    
    public function getBeneficiaryByUserIdCustomerId($user_id, $customer_id)
    {


        $beneficiaries = $this->cableBeneficiaryRepository->fetchByCustomerIdUserId($customer_id, $user_id);


        return $this->successResponse(
            data: $beneficiaries,
        );

    }
    

    public function storeBeneficiary($request, $user)
    {

        /* $validated = $request->validated();

        $wallet = $user->wallet($validated['wallet_id']);

        if(!$this->checkWallet($wallet))
        {
            return $this->errorResponse;
        }

        $provider = json_decode($this->dataDecrypt($validated['snug_id']));

        if(!$provider)
        {
            return $this->errorResponse(
                422,
                "Validation error",
                [
                    'snug_id' => "Invalid snug id"
                ], 
            );
        }


        $service = json_decode($user->service->bill)->electricity;
        $this->customer_id = $validated['customer_id'];
        $this->customer_name = $validated['customer_name'];
        $this->amount = $validated['amount'];
        $this->add_beneficiary = $validated['add_beneficiary'] ?? false;
        $this->meter_type = $validated['meter_type'];
        $this->reference_no = $this->generateReferenceNo();
        $this->transaction_no = $validated['transaction_no'];
        $this->snug_id = $validated['snug_id'];
        
        $this->commission = ($provider->commission / 100) * $this->amount;
        $this->discount = ($provider->discount / 100) * $this->amount;
        $this->total_amount = $this->amount;
        $this->fee = $this->getFee($user);

        $user->level->daily_unused -= $this->amount;
        $user->level->monthly_unused -= $this->amount;

        $wallet->balance -= $this->amount;



        if(!$service->allow )
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

        
        // Transaction
        $transaction = new Transaction;
        $transaction->id = Str::uuid();
        $transaction->number = $this->transaction_no;
        $transaction->amount = $this->amount;
        $transaction->fee = $this->fee;
        $transaction->profit = $this->commission - $this->discount;
        $transaction->discount = $this->discount;
        $transaction->total = $this->total_amount;
        $transaction->reference_no = $this->reference_no;
        $transaction->service_type = ServiceTypeEnum::Electricity->value;
        $transaction->status = TransactionStatusEnum::Processing->value;
        $transaction->remark = "Pending";
        $transaction->user_id = $user->id;


        // Airtime
        $electricity = new Electricity;
        $electricity->amount = $this->amount;
        $electricity->customer_id = $this->customer_id;
        $electricity->customer_name = $this->customer_name;
        $electricity->provider_name = $provider->name;
        $electricity->transaction_id = $transaction->id;
        $electricity->user_id = $user->id;


        $apiData = [
            "data" => json_encode([
                "process_type" => "batch",
                "walletId" => env('PSG_NGN_DEFAULT_WALLET'),
                "customer_id" => $this->customer_id,
                "amount" => $this->amount,
                "meter_type" => $this->meter_type,
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
        $this->electricityRepository->store($electricity);

        // Beneficiary
        if($this->add_beneficiary)
        {
            $newBeneficiary = new ElectricityBeneficiary;
            $newBeneficiary->customer_id = $this->customer_id;
            $newBeneficiary->customer_name = $this->customer_name;
            $newBeneficiary->provider_name = $provider->name;
            $newBeneficiary->user_id = $user->id;

            // Check if beneficiary already exist
            $beneficiary = $this->electricityBeneficiaryRepository->fetchByCustomerId($this->customer_id, $user->id)->first();

            if(!$beneficiary)
            {
                $this->electricityBeneficiaryRepository->store($newBeneficiary);
            }
        } */

        
        /* $transfer = $this->curlPostApi($apiData);

        if(!$transfer->success)
        {
            return $this->errorResponse(
                message: $transfer->message,
                errors: $transfer->errors,
            );
        }*/

        DB::commit();

        // Send Notification
        /* $sendEmail = $this->sendElectricityPurchaseSuccessEmail($transaction);

        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message,
                errors: $sendEmail->errors,
            );
        }


        return $this->successResponse(
            message: 'Successful',
            data: new ElectricityResource($transaction), 
        ); */
    }


    
    public function updateBeneficiary($request, $id)
    {
        $validated = $request->validated();

        /* $user = $this->electricityRepository->fetch($id)->firstOrFail();

        $update = $this->electricityRepository->update($user, $validated);

        return $this->successResponse(
            new AccountsResource($update), 
            'Updated Successful'
        ); */
    }
    
    public function deleteBeneficiary(string $id){

        /* $user = $this->electricityRepository->fetch($id)->firstOrFail();

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
        ); */
    }

    
}