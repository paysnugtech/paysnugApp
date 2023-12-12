<?php 


namespace App\Implementations\Services;

use App\Enums\TokenTypeEnum;
use App\Http\Resources\NotificationsResource;
use App\Http\Resources\ProfilesResource;
use App\Http\Resources\UsersResource;
use App\Http\Resources\WalletsResource;
use App\Interfaces\Repositories\IAccountRepository;
use App\Interfaces\Repositories\IAddressRepository;
use App\Interfaces\Repositories\IBankRepository;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Repositories\IManagerRepository;
use App\Interfaces\Repositories\INotificationRepository;
use App\Interfaces\Repositories\IProfileRepository;
use App\Interfaces\Repositories\IRoleRepository;
use App\Interfaces\Repositories\ITokenRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IVerificationRepository;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Services\IUserService;
use App\Models\Address;
use App\Traits\AccountTrait;
use App\Traits\CreateAddress;
use App\Traits\EmailTrait;
use App\Traits\ResponseTrait;
use App\Traits\StatusTrait;
use App\Traits\StoreVerification;
use App\Traits\UserTrait;
use App\Traits\WalletTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Policies\UserPolicy;

class UserService implements IUserService
{
    use AccountTrait,
        CreateAddress,
        EmailTrait,
        ResponseTrait,
        StatusTrait,
        StoreVerification,
        UserTrait,
        WalletTrait;

    protected $accountRepository;
    protected $addressRepository;
    protected $bankRepository;
    protected $countryRepository;
    protected $expires_in;
    protected $managerRepository;
    protected $notificationRepository;
    protected $profileRepository;
    protected $roleRepository;
    protected $tokenRepository;
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
        ITokenRepository $tokenRepository, 
        IUserRepository $userRepository, 
        UserPolicy $userPolicy, 
        IVerificationRepository $verificationRepository,
        IWalletRepository $walletRepository,
    )
    {
        $this->expires_in = env('TOKEN_EXPIRES');
        $this->accountRepository = $accountRepository;
        $this->addressRepository = $addressRepository;
        $this->bankRepository = $bankRepository;
        $this->countryRepository = $countryRepository;
        $this->managerRepository = $managerRepository;
        $this->notificationRepository = $notificationRepository;
        $this->profileRepository = $profileRepository;
        $this->roleRepository = $roleRepository;
        $this->tokenRepository = $tokenRepository;
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
    
    public function getWallet($user){

        $resource = WalletsResource::collection($user->wallets);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function changePin($request, $user)
    {
        $validated = $request->validated();
        $new_pin = $validated['new_pin'];
        // $token = $validated['token'];
        $type = TokenTypeEnum::ChangePin->value;

        /* $tokenObj = $this->tokenRepository->fetchByEmailNumberType($user->email, $token, $type)->first();

        if(!$tokenObj)
        {
            return $this->errorResponse(
                422, 
                'Not found error'
            );
        }


        if($this->tokenExpire($tokenObj))
        {
            return $this->errorResponse(
                400,
                'Token validation error',
                [
                    'token' => 'Token Expired',
                ], 
            );
        } */


        if (!Hash::check($validated['current_pin'], $user->pin)) 
        {
            return $this->errorResponse(
                message: 'Pin validation error',
                errors: [
                    'current_pin'=> 'Current pin is not correct.',
                ]
            );
        }

        $user->pin = bcrypt($new_pin);

        DB::beginTransaction();

            $resetPin = $this->userRepository->store($user);

            if(!$resetPin)
            {
                return $this->errorResponse(
                    message: 'Unable to reset Pin'
                );
            }

            // $this->tokenRepository->delete($tokenObj);

        DB::commit();

        

        // Sent the registration token to email/phone
        $sendEmail = $this->sendChangePinSuccessEmail($user);


        if(!$sendEmail->success)
        {
            /* return $this->errorResponse(
                message: $sendEmail->message
            ); */
        }


        return $this->successResponse(
            message: 'Pin change successful',
            // data: new UsersResource($user), 
        );
    }
    
    public function resetPin($request, $user)
    {
        $validated = $request->validated();
        $pin = $validated['pin'];
        $token = $validated['token'];
        $type = TokenTypeEnum::ResetPin->value;

        $tokenObj = $this->tokenRepository->fetchByEmailNumberType($user->email, $token, $type)->first();

        if(!$tokenObj)
        {
            return $this->errorResponse(
                422, 
                'Not found error'
            );
        }


        $difference = Carbon::now()->diffInMinutes($tokenObj->created_at);

        if($difference > $this->expires_in)
        {
            return $this->errorResponse(
                400,
                'Token validation error',
                [
                    'token' => 'Token Expired',
                ], 
            );
        }


        if (Hash::check($pin, $user->pin)) 
        {
            return $this->errorResponse(
                message: 'Pin validation error',
                errors: [
                    'pin'=> 'New pin and old pin cannot be the same.',
                ]
            );
        }

        $user->pin = bcrypt($pin);

        DB::beginTransaction();

            $resetPin = $this->userRepository->store($user);

            if(!$resetPin)
            {
                return $this->errorResponse(
                    message: 'Unable to reset Pin'
                );
            }

            $this->tokenRepository->delete($tokenObj);

        DB::commit();

        

        // Sent the registration token to email/phone
        $sendEmail = $this->sendResetPinSuccessEmail($user);


        if(!$sendEmail->success)
        {
            /* return $this->errorResponse(
                message: $sendEmail->message
            ); */
        }


        return $this->successResponse(
            message: 'Pin reset successful',
            // data: new UsersResource($user), 
        );
    }
    
    public function setPin($request, $user)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        $setPin = $this->userRepository->update($user, $validated);

        DB::commit();

        return $this->successResponse(
            new UsersResource($user), 
            'Pin Set Successful'
        );
    }


    public function storeFingerPrint($request, $user){

        $validated = $request->validated();

        $user->finger_print = $validated['finger_print'];

        DB::beginTransaction();

            $store = $this->userRepository->store($user);

            if(!$store)
            {
                
                return $this->errorResponse( 
                    message: 'Unable to store finger print'
                );
            }

        DB::commit();

        return $this->successResponse( 
            message: 'Finger Print Set Successful'
        );
    }
    
    public function updateNotification($request, $user)
    {
        $validated = $request->validated();

        $notification = $user->notification;

        DB::beginTransaction();

        $update = $this->notificationRepository->update($notification, $validated);
        
        DB::commit();

        return $this->successResponse(
            new NotificationsResource($update), 
            'Updated Successful'
        );
    }
    
    public function updateProfile($request, $user)
    {
        $validated = $request->validated();

        $profile = $user->profile;

        $validated['image'] = $validated['profile_image'] ?? $profile->image;
        // $validated['country'] = $country->name;

        DB::beginTransaction();

        $updateProfile = $this->profileRepository->update($profile, $validated);
    
        DB::commit();

        $newProfile = $this->profileRepository->get($updateProfile->id)->firstOrFail();

        return $this->successResponse(
            new ProfilesResource($updateProfile), 
            'Updated Successful'
        );
    }
    
    public function updateUser($request, $user)
    {
        $validated = $request->validated();

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
    
    public function deleteUser($user){

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