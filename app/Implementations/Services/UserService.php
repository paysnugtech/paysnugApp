<?php 


namespace App\Implementations\Services;

use App\Http\Resources\NotificationsResource;
use App\Http\Resources\ProfilesResource;
use App\Http\Resources\UsersResource;
use App\Interfaces\Repositories\IAccountRepository;
use App\Interfaces\Repositories\IAddressRepository;
use App\Interfaces\Repositories\IBankRepository;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Repositories\IManagerRepository;
use App\Interfaces\Repositories\INotificationRepository;
use App\Interfaces\Repositories\IProfileRepository;
use App\Interfaces\Repositories\IRoleRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IVerificationRepository;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Services\IUserService;
use App\Traits\StoreVerification;
use Illuminate\Http\Request;
use App\Models\Address;
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
use Illuminate\Support\Str;
use App\Policies\UserPolicy;


class UserService implements IUserService
{
    use CreateUser,  
        CreateAccount,
        CreateAddress,
        CreateSnugAccount,
        CreateDefaultWallet,
        ErrorResponse,
        StoreVerification,
        SuccessResponse, 
        TokenResponse, 
        UploadProfilePicture;

    protected $accountRepository;
    protected $addressRepository;
    protected $bankRepository;
    protected $countryRepository;
    protected $managerRepository;
    protected $notificationRepository;
    protected $profileRepository;
    protected $roleRepository;
    protected $userRepository;
    protected $userPolicy;
    protected $verificationRepository;
    protected $walletRepository;

    public function __construct(
        IAccountRepository $accountRepository, 
        IAddressRepository $addressRepository, 
        IBankRepository $bankRepository, 
        ICountryRepository $countryRepository, 
        IManagerRepository $managerRepository, 
        INotificationRepository $notificationRepository, 
        IProfileRepository $profileRepository, 
        IRoleRepository $roleRepository, 
        IUserRepository $userRepository, 
        UserPolicy $userPolicy, 
        IVerificationRepository $verificationRepository,
        IWalletRepository $walletRepository,
    )
    {

        $this->accountRepository = $accountRepository;
        $this->addressRepository = $addressRepository;
        $this->bankRepository = $bankRepository;
        $this->countryRepository = $countryRepository;
        $this->managerRepository = $managerRepository;
        $this->notificationRepository = $notificationRepository;
        $this->profileRepository = $profileRepository;
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
        $this->userPolicy = $userPolicy;
        $this->verificationRepository = $verificationRepository;
        $this->walletRepository = $walletRepository;
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
    
    public function setPin($request, $id)
    {
        $validated = $request->validated();

        $user = $this->userRepository->get($id)->firstOrFail();

        // $token = Str::random(6);
        $token = random_int(101010, 999999);

        DB::beginTransaction();

        $setPin = $this->userRepository->update($user, $validated);

        DB::commit();

        return $this->successResponse(
            new UsersResource($user), 
            'Pin Set Successful'
        );
    }


    public function storeFingerPrint($request, $id){

        $validated = $request->validated();

        $user = $this->userRepository->get($id)->firstOrFail();

        DB::beginTransaction();

        $setPin = $this->userRepository->update($user, $validated);

        DB::commit();

        return $this->successResponse(
            new UsersResource($user), 
            'Finger Print Set Successful'
        );
    }
    
    public function storeUser($request){


        DB::beginTransaction();

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


        // Store verification
        $verification = $this->StoreVerification($newUser);

        
        DB::commit();

        // send notification
        

        // get created user
        $user = $this->userRepository->get($newUser->id)->firstOrFail();

        $token = Auth::login($user);


        return $this->tokenResponse(
            new UsersResource($user),
            $token,
            'Created Successful'
        );
    }
    
    public function updateNotification($request, $id)
    {
        $validated = $request->validated();

        $notification = $this->notificationRepository->getByUserId($id)->firstOrFail();

        DB::beginTransaction();

        $update = $this->notificationRepository->update($notification, $validated);
        
        DB::commit();

        return $this->successResponse(
            new NotificationsResource($update), 
            'Updated Successful'
        );
    }
    
    public function updateProfile($request, $id)
    {
        $validated = $request->validated();

        $profile = $this->profileRepository->getByUserId($id)->firstOrFail();

        $validated['image'] = $validated['profile_image'] ?? $profile->image;
        // $validated['country'] = $country->name;

        DB::beginTransaction();

        $updateProfile = $this->profileRepository->update($profile, $validated);
        

        $updateProfile->address->update($validated);


        DB::commit();

        $newProfile = $this->profileRepository->get($updateProfile->id)->firstOrFail();

        return $this->successResponse(
            new ProfilesResource($updateProfile), 
            'Updated Successful'
        );
    }
    
    public function updateUser($request, $id)
    {
        $validated = $request->validated();

        $user = $this->userRepository->get($id)->firstOrFail();
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
    
    public function deleteUser(string $id){
        
        $user = $this->userRepository->get($id)->firstOrFail();

        if (!$this->userPolicy->delete(Auth::user(), $user)) {
            return $this->errorResponse(
                '', 
                'You are not authorized to delete this user.'
            );
        }

        $delete = $this->userRepository->delete($user);

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