<?php 


namespace App\Implementations\Services;

use App\Enums\DeviceStatusEnum;
use App\Http\Resources\NotificationsResource;
use App\Http\Resources\ProfilesResource;
use App\Http\Resources\UsersResource;
use App\Interfaces\Repositories\IDeviceRepository;
use App\Interfaces\Repositories\IDeviceTokenRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IVerificationRepository;
use App\Interfaces\Services\IDeviceService;
use App\Models\Device;
use App\Traits\StoreVerification;
use Illuminate\Http\Request;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;
use App\Traits\TokenResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Policies\UserPolicy;
use Illuminate\Support\Carbon;

class DeviceService implements IDeviceService
{
    use ErrorResponse,
        StoreVerification,
        SuccessResponse, 
        TokenResponse;

    protected $deviceRepository;
    protected $deviceTokenRepository;
    protected $userRepository;
    protected $verificationRepository;

    public function __construct(
        IDeviceRepository $deviceRepository, 
        IDeviceTokenRepository $deviceTokenRepository, 
        IUserRepository $userRepository, 
        IVerificationRepository $verificationRepository
    )
    {

        $this->deviceRepository = $deviceRepository;
        $this->deviceTokenRepository = $deviceTokenRepository;
        $this->userRepository = $userRepository;
        $this->verificationRepository = $verificationRepository;
    }
    
    
    public function deleteDevice(string $id){
        
        $user = $this->userRepository->get($id)->firstOrFail();

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

    public function getDevice(string $id){
        
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
    
    public function getAllDevice(){

        $user = $this->userRepository->getAll();

        if($user->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = UsersResource::collection($user);

        return $this->successResponse($resource, 'Successful');

    }
    
    
    public function storeDevice($request){

        $validated = $request->validated();

        
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


        // get User
        $user = $this->userRepository->getByEmail($validated['email'])->firstOrFail();

    

        DB::beginTransaction();

        // Store verification
        $device = new Device;
        $device->device_name = $validated['device_name'];
        $device->device_id = $validated['device_id'];
        $device->device_type = $validated['device_type'];
        $device->platform = $validated['platform'];
        $device->ip = $request->ip;
        $device->status = DeviceStatusEnum::Verify->value;
        $device->user_id = $user->id;

        $this->deviceRepository->save($device);
        
        
        DB::commit();

        // send notification
        

        // get created user
        /* $user = $this->userRepository->get($newUser->id)->firstOrFail();

        $token = Auth::login($user);


        return $this->tokenResponse(
            new UsersResource($user),
            $token,
            'Created Successful'
        ); */
    }
    
    
    
    public function updateDevice($request, $id)
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
    
}