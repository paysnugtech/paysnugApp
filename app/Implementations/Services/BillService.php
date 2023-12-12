<?php 


namespace App\Implementations\Services;

use App\Enums\EncryptTypeEnum;
use App\Http\Resources\BillsResource;
use App\Http\Resources\VerificationsResource;
use App\Interfaces\Repositories\IAddressRepository;
use App\Interfaces\Repositories\IBillRepository;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Repositories\INotificationRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IVerificationRepository;
use App\Interfaces\Services\IBillService;
use App\Models\Address;
use App\Traits\EncryptionTrait;
use App\Traits\ResponseTrait;
use App\Traits\WalletTrait;
use App\Traits\WhereHouseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class BillService implements IBillService
{
    use ResponseTrait,
        EncryptionTrait,
        WalletTrait,
        WhereHouseTrait;
    
    
    
    protected $amount;
    protected $amount_paid;
    protected $package_id;

    protected $addressRepository;
    protected $billRepository;
    protected $countryRepository;
    protected $notificationRepository;
    protected $profileRepository;
    protected $userRepository;
    protected $verificationRepository;

    
    public function __construct( 
        IAddressRepository $addressRepository, 
        IBillRepository $billRepository, 
        ICountryRepository $countryRepository, 
        INotificationRepository $notificationRepository,
        IUserRepository $userRepository, 
        IVerificationRepository $verificationRepository
    )
    {

        $this->addressRepository = $addressRepository;
        $this->billRepository = $billRepository;
        $this->countryRepository = $countryRepository;
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
        $this->verificationRepository = $verificationRepository;
    }
    
    
    
    public function list(){

        $user = $this->billRepository->fetchAll();

        if($user->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = BillsResource::collection($user);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function getBill(string $id){
        

        $bill = $this->billRepository->fetch($id)->firstOrFail();

        return $this->successResponse(
            new BillsResource($bill), 
            'Successful'
        );
    }


    public function payBill($request, $user)
    {
        $validated = $request->validated();

        $this->package_id = $validated['package_id'];

        
        $name = "package_id";
        $verify = $this->checkEncryption($this->package_id, $name);


        if(!$verify->success)
        {

            return $this->errorResponse(
                $verify->code,
                $verify->message,
                $verify->errors,
            );
        }
        


        


        $wallet = $user->wallet($validated['wallet_id']);

        if(!$this->checkWallet($wallet))
        {
            return $this->errorResponse;
        }

        
    }


    public function payData($request, $user)
    {
        $validated = $request->validated();

        $this->package_id = $validated['package_id'];

        
        $name = "package_id";
        $verify = $this->checkEncryption($this->package_id, $name);


        if(!$verify->success)
        {

            return $this->errorResponse(
                $verify->code,
                $verify->message,
                $verify->errors,
            );
        }
        
        $provider = $verify->data;


        if($provider->enc_type === EncryptTypeEnum::DATA_LIST->value)
        {
            print_r($provider);
            
        }
        else
        {
            
            return $this->errorResponse(
                422,
                'Validation error',
                [
                    $name => "Invalid id type"
                ],
            );
        }


        $wallet = $user->wallet($validated['wallet_id']);

        if(!$this->checkWallet($wallet))
        {
            return $this->errorResponse;
        }

        
    }


    public function storeUtilityBill($request, $user)
    {
        $validated = $request->validated();

        $verification = $user->verification;

        if (!$request->file('bill')->isValid()) 
        {
            return $this->errorResponse(
                422, 
                'Invalid bill uploaded'
            );
        }

        $uploadFile = $request->file('bill');

        if($verification->bill->is_verified)
        {
            return $this->errorResponse(
                message: 'User utility bill already verified'
            );
        }



        // Verify the bill
        $apiData = [
            'data' => json_encode([
                'doc_type' => 'utility',
                'file' => base64_encode(file_get_contents($uploadFile)),
                'name' => $user->id
            ]),
            'header' => [
                'Accept: application/json',
                'Content-Type: application/json',
                'X-Secret-Key: '. env('PSG_SECRET_KEY')
            ],
            'url' => env('PSG_UPLOAD_UTILITY_BILL_URL')
        ];


        $uploadBill = $this->curlPostApi($apiData);


        if(!$uploadBill->success)
        {
            return $this->errorResponse(
                message: $uploadBill->message,
                errors: $uploadBill->errors
            );
        }

        $verification->attempt += 1;
        $verification->updated_by = $user->id;

        
        $bill = $verification->bill;
        $bill->url = $uploadBill->data->doc_url;
        $bill->updated_by = $user->id;


        $address = new Address;
        $address->city = $validated['city'];
        $address->country = $validated['country'];
        $address->postal_code = $validated['postal_code'] ?? NULL;
        $address->profile_id = $user->profile->id;
        $address->street = $validated['street'];


        // store the bvn
        DB::beginTransaction();
        
        $this->addressRepository->store($address);

        $this->verificationRepository->store($verification);

        $updateBill = $this->billRepository->store($bill);

        if(!$updateBill)
        {
            return $this->errorResponse(
                message: 'Unable to store bill'
            );
        }

        DB::commit();

        return $this->successResponse(
            message: 'Successful',
            data: new BillsResource($bill), 
        );
    }


    public function updateBill($request)
    {

    }

    public function verifyBill($request, $user)
    {
        $validated = $request->validated();

        $verification = $user->verification;

        if (!$request->file('bill')->isValid()) 
        {
            return $this->errorResponse(
                [], 
                'Invalid bill uploaded'
            );
        }

        $uploadFile = $request->file('bill');

        if($verification->bill->is_verified)
        {
            return $this->errorResponse(
                [], 
                'User utility bill already verified'
            );
        }



        // Verify the bill
        $data = [
            'doc_type' => 'utility',
            'file' => base64_encode(file_get_contents($uploadFile)),
            'name' => $user->id,
        ];



        $uploadBill = $this->uploadUtilityBill($data);
        // $uploadBill = $this->uploadBill1($data);


        if(!$uploadBill->success)
        {
            return $this->errorResponse(
                [], 
                $uploadBill->message
            );
        }

        $verification->attempt += 1;
        $verification->updated_by = $user->id;

        
        $bill = $verification->bill;
        $bill->url = $uploadBill->data->url;
        $bill->updated_by = $user->id;

        $address = new Address;
        $address->city = $validated['city'];
        $address->country = $validated['country'];
        $address->postal_code = $validated['postal_code'] ?? NULL;
        $address->profile_id = $user->profile->id;
        $address->street = $validated['street'];


        // store the bvn
        DB::beginTransaction();
        
        $this->addressRepository->store($address);

        $this->verificationRepository->store($verification);

        $updateBill = $this->billRepository->store($bill);

        if(!$updateBill)
        {
            return $this->errorResponse(
                [], 
                'Unable to update bill'
            );
        }

        DB::commit();

        return $this->successResponse(
            new VerificationsResource($verification), 
            'Successful'
        );
    }


    
    public function removeBill(string $id){
        
        $bill = $this->billRepository->fetch($id)->firstOrFail();

        $delete = $this->billRepository->delete($bill);

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
    
}