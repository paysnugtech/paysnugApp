<?php 


namespace App\Implementations\Services;


use App\Enums\DeviceStatusEnum;
use App\Enums\EncryptTypeEnum;
use App\Enums\VerificationEnum;
use App\Http\Resources\RegisterResource;
use App\Interfaces\Repositories\IAccountRepository;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Repositories\IDeviceRepository;
use App\Interfaces\Repositories\ILevelRepository;
use App\Interfaces\Repositories\IManagerRepository;
use App\Interfaces\Repositories\INotificationRepository;
use App\Interfaces\Repositories\IRegisterTokenRepository;
use App\Interfaces\Repositories\IRoleRepository;
use App\Interfaces\Repositories\IServiceRepository;
use App\Interfaces\Repositories\IServiceUserRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IVerificationRepository;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Repositories\IWalletTypeRepository;
use App\Interfaces\Services\IRegisterService;
use App\Models\Device;
use App\Models\ServiceUser;
use App\Traits\AccountTrait;
use App\Traits\CreateAddress;
use App\Traits\CreateDefaultWallet;
use App\Traits\EmailTrait;
use App\Traits\EncryptionTrait;
use App\Traits\ResponseTrait;
use App\Traits\StatusTrait;
use App\Traits\TokenTrait;
use App\Traits\UserTrait;
use App\Traits\WalletTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class RegisterService implements IRegisterService
{
    use AccountTrait,
        CreateAddress,
        CreateDefaultWallet,
        EncryptionTrait,
        EmailTrait,
        ResponseTrait, 
        StatusTrait, 
        TokenTrait, 
        UserTrait,  
        WalletTrait;
    
    protected $accountRepository;
    protected $countryRepository;
    protected $deviceRepository;
    protected $errorResponse;
    protected $levelRepository;
    protected $managerRepository;
    protected $notificationRepository;
    protected $registerTokenRepository;
    protected $roleRepository;
    protected $serviceRepository;
    protected $serviceUserRepository;
    protected $userRepository;
    protected $verificationRepository;
    protected $walletRepository;
    protected $walletTypeRepository;
    

    public function __construct(
        IAccountRepository $accountRepository, 
        ICountryRepository $countryRepository,
        IDeviceRepository $deviceRepository,
        ILevelRepository $levelRepository,
        IManagerRepository $managerRepository, 
        // IProfileRepository $profileRepository,
        INotificationRepository $notificationRepository,
        IRegisterTokenRepository $registerTokenRepository,
        IRoleRepository $roleRepository, 
        IServiceRepository $serviceRepository,
        IServiceUserRepository $serviceUserRepository,
        IUserRepository $userRepository,
        IVerificationRepository $verificationRepository,
        IWalletRepository $walletRepository,
        IWalletTypeRepository $walletTypeRepository,
    )
    {
        $this->accountRepository = $accountRepository;
        $this->countryRepository = $countryRepository;
        $this->deviceRepository = $deviceRepository;
        $this->levelRepository = $levelRepository;
        $this->managerRepository = $managerRepository;
        $this->notificationRepository = $notificationRepository;
        $this->registerTokenRepository = $registerTokenRepository;
        $this->roleRepository = $roleRepository;
        $this->serviceRepository = $serviceRepository;
        $this->serviceUserRepository = $serviceUserRepository;
        $this->userRepository = $userRepository;
        $this->verificationRepository = $verificationRepository;
        $this->walletRepository = $walletRepository;
        $this->walletTypeRepository = $walletTypeRepository;
    }
    
    


    public function storeRegistrationToken($request){

        $validated = $request->validated();

        $oldToken = $this->registerTokenRepository->get($validated['email'])->first();

        if($oldToken)
        {

            // Token already sent and not expired
            if(!$this->tokenExpire($oldToken))
            {

                return $this->errorResponse(
                    message: 'Token already already sent, wait for '. $oldToken->expire_in .' minutes to generate another token'
                );
                
            }
            else
            {
                // Delete expired token
                $this->registerTokenRepository->delete($oldToken);
            }
            
        }

        $token = random_int(101010, 999999);


        $data = [
            'email' => $validated['email'],
            'token' => Hash::make($token),
            'expire_in' => 3,
            'created_at' => now()
        ];


        // Sent the registration token to email/phone
        $sendEmail = $this->registrationTokenEmail($validated['email'], $token);


        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message
            );
        }

        DB::beginTransaction();

        $this->registerTokenRepository->create($data);

        DB::commit();


        return $this->successResponse(
            message: 'Token sent Successful'
        );
    }
    


    public function storeUser($request){

        $validated = $request->validated();
        $validated['wallet_name'] = "Default";

        $token_signature = $validated['token_signature'];
        $name = "token_signature";
        $type = EncryptTypeEnum::REGISTRATION_TOKEN->value;

        $signature = $this->checkEncryption($token_signature, $type, $name);

        if(!$signature)
        {
            return $this->errorResponse;
        }


        if($signature->email !== $validated['email'])
        {
            
            return $this->errorResponse(
                message: 'Invalid token email'
            );
        }


        // get user country
        $country = $this->countryRepository->get($validated['country_id'])->firstOrFail();


        // get Default wallet type
        // $walletType = $this->walletTypeRepository->getByName('Default')->firstOrFail();



        /* $validated['country'] = $country->name;
        $validated['role_id'] = $role->id;
        $validated['manager_id'] = $manager->id;
        $validated['wallet_type_id'] = $walletType->id; */

        

        // Create Account
        $accountNumber = $this->getAccountFromNo($validated['phone_no']);

        $account = $this->accountRepository->getByAccountNo($accountNumber)->first();

        if($account)
        {
            return $this->errorResponse(
                message: 'Account Already exist'
            );
        }

        // user
        $userObj = $this->CreateUserObject($validated);


        // Create Level Object
        $level = $this->CreateUserLevelObject($userObj);
        

        // Create Wallet Object
        $wallet = $this->CreateWalletObject($userObj, $validated);

        // Create Token Object
        $token = $this->registerTokenRepository->get($validated['email'])->firstOrFail();


        // create device instance
        $device = new Device;
        $device->name = $validated['device_name'];
        $device->device_id = $validated['device_id'];
        $device->type = $validated['device_type'];
        $device->platform = $validated['platform'];
        $device->signature = Str::random(64);
        $device->ip = $request->ip();
        $device->status = DeviceStatusEnum::Verify->value;
        $device->user_id = $userObj->id;


        
        DB::beginTransaction();

        // store user
        $this->userRepository->store($userObj);

        // store profile
        $userObj->profile()->create($validated);

        // store level
        $this->levelRepository->store($level);

        // store service
        $this->storeServiceUser($userObj);

        
        // store wallet
        $this->walletRepository->store($wallet);


        // delete registration token
        $this->registerTokenRepository->delete($token);


        // delete registration token
        $this->deviceRepository->store($device);

        

        /* $getAccount = $this->createSnugAccount($wallet);

        if(!$getAccount->success)
        {
            return $this->errorResponse(
                $getAccount->code,
                message: $getAccount->message,
                errors: $getAccount->errors,
            );
        } */



        /* $account = new Account;
        $account->number = $getAccount->data->account_number;
        $account->provider_name = $getAccount->data->provider_name;
        $account->type = $getAccount->data->account_type;
        $account->user_id = $userObj->id;
        $account->wallet_id = $wallet->id;
        $account->created_by = $userObj->id;

        $this->accountRepository->store($account); */

        // Save Notification
        $notification = $this->notificationRepository->create([
            'user_id' => $userObj->id
        ]);


        // Store verification
        $verification = $this->storeVerification($userObj);


        DB::commit();


        $credentials = $request->only(['email', 'password']);

        $user = $this->LoginUser($credentials);
        
        if (!$user) {

            return $this->errorResponse(
                401,
                'Invalid email or password!', 
            );
        }


        $bearerToken = Auth::refresh();


        
        // Sent the registration successful notification to email/phone
        $sendEmail = $this->registrationSuccessEmail($user);


        if(!$sendEmail->success)
        {
            // return $this->errorResponse(
            //     message: $sendEmail->message,
            //     errors: $sendEmail->errors,
            // );
        }


        // get created user
        // $getUser = $this->userRepository->get($userObj->id)->firstOrFail();


        /* return $this->successResponse( 
            message: 'Created Successful',
            data: new RegisterResource($getUser)
        ); */

        return $this->tokenResponse(
            token: $bearerToken,
            data: new RegisterResource($user),
        );
    }



    
    /*  
    */
    protected function storeVerification($user)
    {

        // Save Notification
        $verification = $this->verificationRepository->create([
            'user_id' => $user->id,
            'email_verified' => VerificationEnum::Verified->value,
            'phone_verified' => VerificationEnum::NotVerified->value,
        ]);


        $verification->bill()->create([
            'user_id' => $user->id
        ]);

        $verification->bvn()->create([
            'user_id' => $user->id
        ]);

        $verification->card()->create([
            'user_id' => $user->id
        ]);

        return $verification; 
    }

    

    public function verifyRegistrationToken($request){

        $validated = $request->validated();
        $email = $validated['email'];
        $token = $validated['token'];

        $db_fetch = $this->registerTokenRepository->get($email)->firstOrFail();

        if (!Hash::check($token, $db_fetch->token)) 
        {
            return $this->errorResponse(
                422,
                'Invalid token.',
            );
        }
        


        if($this->tokenExpire($db_fetch))
        {

            return $this->errorResponse(
                message: 'Token already expired.',
            );
            
        }

        /* DB::beginTransaction();
        
        $this->registerTokenRepository->delete($db_fetch);

        DB::commit(); */


        $signature = [
            "email" => $email,
            "token" => $token,
            "_type" => EncryptTypeEnum::REGISTRATION_TOKEN->value
        ];


        return $this->successResponse(
            message: 'Token verify successful',
            data: [
                'token_signature' => $this->dataEncrypt($signature)
            ]
        );
    }

    

    private function storeServiceUser($user){

        $services = $this->serviceRepository->fetchAll();

        if(!$services)
        {
            return false;
        }

        foreach($services as $service)
        {
            $serviceUser = new ServiceUser;
            $serviceUser->is_allow = 1;
            $serviceUser->is_free = 0;
            $serviceUser->free_count = 0;
            $serviceUser->service_id = $service->id;
            $serviceUser->user_id = $user->id;

            $this->serviceUserRepository->store($serviceUser);
        }
    }
    
    
}