<?php 


namespace App\Implementations\Services;

use App\Http\Resources\UsersResource;
use App\Interfaces\Repositories\IAddressRepository;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Repositories\IProfileRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Services\IProfileService;
use App\Interfaces\Services\IUserService;
use App\Policies\ProfilePolicy;
use App\Traits\ResponseTrait;
use App\Traits\StatusTrait;
use App\Traits\WhereHouseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Policies\UserPolicy;


class ProfileService implements IProfileService
{
    use ResponseTrait,
        StatusTrait,
        WhereHouseTrait;


    protected $addressRepository;
    protected $countryRepository;
    protected $profileRepository;
    protected $userRepository;
    protected $profilePolicy;

    public function __construct(
        IAddressRepository $addressRepository,
        ICountryRepository $countryRepository, 
        IProfileRepository $profileRepository, 
        IUserRepository $userRepository, 
        ProfilePolicy $profilePolicy
    )
    {

        $this->addressRepository = $addressRepository;
        $this->countryRepository = $countryRepository;
        $this->profileRepository = $profileRepository;
        $this->userRepository = $userRepository;
        $this->profilePolicy = $profilePolicy;
    }
    
    public function storeProfile($request){


        DB::beginTransaction();

        $validated = $request->validated();

        // Save User
        $newProfile = $this->profileRepository->create($validated);

        $newProfile->address()->create($validated);


        DB::commit();

        return $this->successResponse(
            message: 'Created Successful'
        );
    }
    

    public function storeProfilePicture($request){

        $validated = $request->validated();

        // Save User
        $profile = $request->user()->profile;

        $picture = $request->file('picture');

        if(!$picture->isValid())
        {
            return $this->errorResponse(
                422, 
                'Picture not valid'
            );
        }


        $requestData = [
            'data' => json_encode([
                'kyc_type' => 'selfie',
                'name' => $profile->user->id,
                'file' => base64_encode(file_get_contents($picture))
            ]),
            'header' => [
                'X-Secret-Key: '. env('PSG_SECRET_KEY'),
                'Accept: application/json',
                'Content-Type: application/json',
            ],
            'url' => env('UPLOAD_PICTURE_ENDPOINT')
        ];


        $uploadPicture = $this->curlPostApi($requestData);

        if(!$uploadPicture->success)
        {
            return $this->errorResponse( 
                message: $uploadPicture->message
            );
        }

        
        DB::beginTransaction();

            $profile->picture_url = $uploadPicture->data->url;

            $store = $this->profileRepository->store($profile);

            if(!$store)
            {
                return $this->ErrorResponse(
                    message: 'Unable to upload profile picture'
                );
            }


        DB::commit();

        return $this->successResponse(
            message: 'Picture uploaded successful'
        );
    }
    
    public function getProfile(string $id){

        $user = $this->profileRepository->get($id)->firstOrFail();

        return $this->successResponse(
            new UsersResource($user), 
            'Successful'
        );
    }
    
    public function getProfileByUserId(string $user_id){

        $user = $this->profileRepository->getByUserId($user_id)->firstOrFail();

        return $this->successResponse(
            new UsersResource($user), 
            'Successful'
        );
    }
    
    public function getAllProfile(){

        $user = $this->userRepository->getAll();

        if($user->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = UsersResource::collection($user);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function updateProfile($request, $id)
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
    
    public function deleteProfile(string $id){
        
        $user = $this->userRepository->get($id)->firstOrFail();

        if (!$this->profilePolicy->delete(Auth::user(), $user)) {
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