<?php 


namespace App\Implementations\Services;

use App\Enums\VerificationEnum;
use App\Http\Resources\BvnsResource;
use App\Http\Resources\CardTypesResource;
use App\Http\Resources\UsersResource;
use App\Http\Resources\VerificationsResource;
use App\Interfaces\Repositories\IAccountRepository;
use App\Interfaces\Repositories\IAddressRepository;
use App\Interfaces\Repositories\IBankRepository;
use App\Interfaces\Repositories\IBillRepository;
use App\Interfaces\Repositories\ICardRepository;
use App\Interfaces\Repositories\ICardTypeRepository;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Repositories\IManagerRepository;
use App\Interfaces\Repositories\INotificationRepository;
use App\Interfaces\Repositories\IProfileRepository;
use App\Interfaces\Repositories\IRoleRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IVerificationRepository;
use App\Interfaces\Services\IVerificationService;
use App\Models\Address;
use App\Models\Card;
use App\Traits\VerificationTrait;
use Illuminate\Http\Request;
use App\Traits\CreateAccount;
use App\Traits\CreateSnugAccount;
use App\Traits\CreateUser;
use App\Traits\CreateAddress;
use App\Traits\CreateDefaultWallet;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;
use App\Traits\TokenResponse;
use App\Traits\UploadProfilePicture;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Policies\UserPolicy;


class VerificationService implements IVerificationService
{
    use CreateUser,  
        CreateAccount,
        CreateAddress,
        CreateSnugAccount,
        CreateDefaultWallet,
        ErrorResponse,
        SuccessResponse, 
        TokenResponse, 
        UploadProfilePicture,
        VerificationTrait;

    protected $accountRepository;
    protected $addressRepository;
    protected $billRepository;
    protected $cardRepository;
    protected $cardTypeRepository;
    protected $countryRepository;
    protected $notificationRepository;
    protected $profileRepository;
    protected $roleRepository;
    protected $userRepository;
    protected $verificationRepository;

    
    public function __construct(
        IAccountRepository $accountRepository, 
        IAddressRepository $addressRepository, 
        IBillRepository $billRepository,  
        ICardRepository $cardRepository,  
        ICardTypeRepository $cardTypeRepository,  
        ICountryRepository $countryRepository, 
        INotificationRepository $notificationRepository,
        IUserRepository $userRepository, 
        IVerificationRepository $verificationRepository
    )
    {

        $this->accountRepository = $accountRepository;
        $this->addressRepository = $addressRepository;
        $this->billRepository = $billRepository;
        $this->cardRepository = $cardRepository;
        $this->cardTypeRepository = $cardTypeRepository;
        $this->countryRepository = $countryRepository;
        $this->notificationRepository = $notificationRepository;
        $this->userRepository = $userRepository;
        $this->verificationRepository = $verificationRepository;
    }
    
    
    
    public function getAllVerification(){

        $user = $this->verificationRepository->getAll();

        if($user->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = UsersResource::collection($user);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function getVerification(string $id){
        
        if($id != Auth::id())
        {
            return $this->errorResponse(
                ['id' => 'Invalid user Id'], 
                'Validation error',
                422
            );
        }

        $user = $this->verificationRepository->get($id)->firstOrFail();

        return $this->successResponse(
            new UsersResource($user), 
            'Successful'
        );
    }
    
    
    public function storeVerification($request){


        /* DB::beginTransaction();

        // Save User
        $newUser = $this->CreateUser($request);

        // Create Wallet
        $newWallet = $this->CreateDefaultWallet($newUser, $request);

        // Create Account
        $account = $this->CreateAccount($newUser, $newWallet);


        if(!$account)
        {
            $account_no = (int)$newUser->phone_no;

            return $this->errorResponse(
                [], 
                'Account number ('. $account_no .') Already taken by another user'
            );
        }


        // Save Notification
        $notification = $this->notificationRepository->create([
            'user_id' => $newUser->id
        ]);

        
        DB::commit();

        // send notification
        

        // get created user
        $user = $this->userRepository->get($newUser->id)->firstOrFail();

        $token = Auth::login($user);


        return $this->tokenResponse(
            new UsersResource($user),
            $token,
            'Created Successful'
        ); */
    }
    
    
    
    public function updateVerification($request, $id)
    {
        $validated = $request->validated();

        $user = $this->verificationRepository->get($id)->firstOrFail();
        // $country = $this->countryRepository->get($validated['country_id'])->firstOrFail();

        $validated['profile_image'] = $validated['profile_image'] ?? $user->profile_image;
        // $validated['country'] = $country->name;
        
       
        DB::beginTransaction();

        $updateUser = $this->userRepository->update($user, $validated);

        $user->address->update($validated);

        DB::commit();


        return $this->successResponse(
            new UsersResource($updateUser), 
            'Updated Successful'
        );
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


    public function verifyBvn($request)
    {
        $validated = $request->validated();

        $user = Auth::user();

        $verification = $this->verificationRepository->getByUserId($user->id)->firstOrFail();

        // Verify the bvn
        


        // store the bvn
        DB::beginTransaction();

        $data = [
            'number' => $validated['bvn'],
            'is_verified' => VerificationEnum::Verified->value,
        ];

        $verification->bvn()->update($data);

        DB::commit();

        return $this->successResponse(
            new BvnsResource($verification->bvn), 
            'Successful'
        );
    }


    public function verifyCard($request)
    {
        $validated = $request->validated();

        $user = Auth::user();

        $verification = $this->verificationRepository->getByUserId($user->id)->firstOrFail();

        // Verify the bvn



        // store the bvn
        DB::beginTransaction();

        $data = [
            'number' => $validated['bvn'],
            'is_verified' => VerificationEnum::Verified->value,
        ];

        $verification->card()->update($data);

        DB::commit();

        return $this->successResponse(
            new VerificationsResource($verification), 
            'Successful'
        );
    }

    
    public function deleteVerification(string $id){
        
        $verification = $this->verificationRepository->get($id)->firstOrFail();

        $delete = $this->verificationRepository->delete($verification);

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