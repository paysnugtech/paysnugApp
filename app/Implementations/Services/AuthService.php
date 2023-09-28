<?php 


namespace App\Implementations\Services;

use App\Enums\DeviceStatusEnum;
use App\Enums\LogStatusEnum;
use App\Http\Resources\UsersResource;
use App\Interfaces\Repositories\IDeviceRepository;
use App\Interfaces\Repositories\IDeviceTokenRepository;
use App\Interfaces\Repositories\ILogRepository;
use App\Interfaces\Repositories\IPasswordResetRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Services\IAuthService;
use App\Models\Device;
use App\Models\DeviceToken;
use App\Models\Log;
use App\Models\PasswordReset;
use App\Traits\ErrorResponse;
use App\Traits\StoreLog;
use App\Traits\SuccessResponse;
use App\Traits\TokenResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AuthService implements IAuthService
{
    use ErrorResponse, StoreLog, SuccessResponse, TokenResponse;

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
        $this->deviceTokenRepository->save($deviceToken);


        return $this->successResponse(
            [],
            'New Device, A token has been sent to your email for device verification'
        );

    }
    
    
    public function loginUser($request)
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


        $deviceToken = $this->deviceTokenRepository->getByEmail($validated['email'])->firstOrFail();

        if($deviceToken->token != $validated['token'])
        {
            return $this->errorResponse(
                [], 
                'Invalid Token'
            );
        }


        // Expire in 5 minutes
        if(now()->subMinutes(5) > $deviceToken->created_at )
        {
            return $this->errorResponse(
                [], 
                'Token already expired'
            );
        }


        $user = Auth::user();


        DB::beginTransaction();

        // Store device
        $device = new Device;
        $device->device_name = $validated['device_name'];
        $device->device_id = $validated['device_id'];
        $device->device_type = $validated['device_type'];
        $device->platform = $validated['platform'];
        $device->ip = $request->ip();
        $device->status = DeviceStatusEnum::Verify->value;
        $device->user_id = $user->id;

        $this->deviceRepository->save($device);
        

        // Store log
        $this->storeLog($user, $request);
        
        
        // Delete the token
        $this->deviceTokenRepository->delete($deviceToken);

        DB::commit();

        // send notification
        
        
        return $this->tokenResponse(
            new UsersResource($user),
            $token,
            'Successful'
        );

    }

    
    public function logoutUser()
    {
        Auth::logout();

        return $this->successResponse([], 'Successfully logged out');
    }
    
    public function refreshToken()
    {
        
        $user = Auth::user();
        $token = Auth::refresh();

        return $this->tokenResponse(
            new UsersResource($user),
            $token,
            'Successful'
        );
    }
    
    public function payload()
    {
        return Auth::payload();
    }
    
    
}