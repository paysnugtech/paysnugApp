<?php 


namespace App\Implementations\Services;

use App\Enums\DeviceStatusEnum;
use App\Enums\LogStatusEnum;
use App\Http\Resources\LoginsResource;
use App\Http\Resources\UsersResource;
use App\Interfaces\Repositories\IDeviceRepository;
use App\Interfaces\Repositories\IDeviceTokenRepository;
use App\Interfaces\Repositories\ILogRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Services\IAuthService;
use App\Models\Device;
use App\Models\DeviceToken;
use App\Models\Log;
use App\Traits\EmailTrait;
use App\Traits\ResponseTrait;
use App\Traits\StatusTrait;
use App\Traits\StoreLog;
use App\Traits\TokenTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AuthService implements IAuthService
{
    use EmailTrait,
        ResponseTrait,
        StatusTrait,
        StoreLog,
        TokenTrait;

    
    protected $expire_in;
    protected $deviceRepository;
    protected $deviceTokenRepository;
    protected $logRepository;
    protected $userRepository;



    public function __construct( 
        IDeviceRepository $deviceRepository, 
        IDeviceTokenRepository $deviceTokenRepository, 
        ILogRepository $logRepository,
        IUserRepository $userRepository
    )
    {
        $this->expire_in = env("DEVICE_TOKEN_EXPIRE_IN");
        $this->deviceRepository = $deviceRepository;
        $this->deviceTokenRepository = $deviceTokenRepository;
        $this->logRepository = $logRepository;
        $this->userRepository = $userRepository;
    }

    
    public function loginToken($request)
    {
        
        $validated = $request->validated();

        $credentials = $request->only(['email', 'password']);

        $token = Auth::attempt($credentials);
        
        if (!$token) {

            return $this->errorResponse(
                [], 
                'Invalid email or password!', 
                401
            );
        }

        $user = Auth::user();

        // generate token
        $token = "".random_int(101010, 999999);
        // $token = Str::random(64);

        $old_token = $this->deviceTokenRepository->getByEmail($user->email)->first();
        
        if($old_token)
        {
            // delete old the token
            $this->deviceTokenRepository->delete($old_token);
        }


        // create deviceToken object
        $deviceToken = new DeviceToken();
        $deviceToken->token = $token;
        $deviceToken->email = $user->email;


        // save the token
        $this->deviceTokenRepository->store($deviceToken);


        return $this->successResponse(
            message: 'New Device, A token has been sent to your email for device verification'
        );

    }
    
    
    public function loginUser($request)
    {
        
        $validated = $request->validated();

        $credentials = $request->only(['email', 'password']);

        $auth = Auth::attempt($credentials);
        
        if (!$auth) {

            return $this->errorResponse(
                401,
                'Invalid email or password!', 
            );
        }


        $user = Auth::user();

        
        // generate token
        $token = "".random_int(101010, 999999);
        // $token = Str::random(64);
        $hash_token = Hash::make($token);

        $device = $this->deviceRepository->getByUserId($user->id)->first();

        // print_r($device);

        if(!$device)
        {

            $old_token = $this->deviceTokenRepository->getByEmail($user->email)->first();
            
            if($old_token)
            {


                if(!$this->tokenExpire($old_token))
                {

                    return $this->errorResponse(
                        $this->statusCode('token_sent'),
                        'Token validation error',
                        [
                            'token' => 'Token already already sent, wait for '. $old_token->expire_in .' minutes to generate another token',
                        ], 
                    );
                    
                }


                // delete old the token
                $this->deviceTokenRepository->delete($old_token);
            }


            // create deviceToken object
            $deviceToken = new DeviceToken();
            $deviceToken->token = $hash_token;
            $deviceToken->email = $user->email;
            $deviceToken->expire_in = $this->expire_in;
            
            // Sent the device token notification to email/phone
            $sendEmail = $this->deviceVerificationTokenEmail($user, $token);

            if(!$sendEmail->success)
            {
                return $this->errorResponse(
                    message: 'Email error!'
                );
            }

            // save the token
            $this->deviceTokenRepository->store($deviceToken);

            return $this->errorResponse(
                $this->statusCode('new_device'),
                message: 'This is a new device, an OTP has been sent to you email to verify the device!'
            );
        }
        else if($device->signature !== $validated['signature'])
        {

            // delete the existing device
            $this->deviceRepository->delete($device);


            // create deviceToken object
            $deviceToken = new DeviceToken();
            $deviceToken->token = $hash_token;
            $deviceToken->email = $user->email;
            $deviceToken->expire_in = $this->expire_in;

            
            // Sent the registration successful notification to email/phone
            $sendEmail = $this->deviceVerificationTokenEmail($user, $token);
            
            if(!$sendEmail->success)
            {
                return $this->errorResponse(
                    message: 'Email error!'
                );
            }
            
            // save the token
            $this->deviceTokenRepository->store($deviceToken);

            return $this->errorResponse(
                $this->statusCode('new_device'),
                message: 'This is a new device, an OTP has been sent to you email to verify the device!'
                
            );
        }

        
        DB::beginTransaction();
        
        // Store log
        $this->storeLog($user, $request);

        DB::commit();


        // send login success notification
        $sendEmail = $this->loginSuccessEmail($user);

        $bearerToken = Auth::refresh();

        
        
        return $this->tokenResponse(
            token: $bearerToken,
            data: new LoginsResource($user),
        );

    }

    
    public function logoutUser()
    {
        $user = Auth::user();

        $log = $this->logRepository->getByUserId($user->id)->first();

        if($log)
        {
            $timeNow = now();
            $log->logout_at = $timeNow;
            $log->deleted_at = $timeNow;

            $this->deviceRepository->delete($log->user->device);

            $this->logRepository->store($log);
        }
        

        Auth::logout();

        return $this->successResponse([], 'Successfully logged out');
    }
    
    public function refreshToken()
    {
        
        $user = Auth::user();

        $token = Auth::refresh();

        return $this->tokenResponse(
            message: 'Successful',
            data: new UsersResource($user),
            token: $token,
        );
    }
    
    public function payload()
    {
        return Auth::payload();
    }
    
    
}