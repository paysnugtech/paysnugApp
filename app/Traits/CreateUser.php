<?php 

namespace App\Traits;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Repositories\IManagerRepository;
use App\Interfaces\Repositories\IProfileRepository;
use App\Interfaces\Repositories\IRoleRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Models\Profile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

trait CreateUser{

    protected $countryRepository;
    protected $managerRepository;
    protected $profileRepository;
    protected $roleRepository;
    protected $userRepository;

    public function __construct(
            ICountryRepository $countryRepository, 
            IManagerRepository $managerRepository, 
            IProfileRepository $profileRepository,
            IRoleRepository $roleRepository, 
            IUserRepository $userRepository, 
        ){
        $this->countryRepository = $countryRepository;
        $this->managerRepository = $managerRepository;
        $this->profileRepository = $profileRepository;
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }
    

    protected function CreateUser($request)
    {
        
        $validated = $request->validated();

        $country = $this->countryRepository->get($validated['country_id'])->firstOrFail();
        $role = $this->roleRepository->getByName('User')->firstOrFail();
        $manager = $this->managerRepository->getRandomManager()->firstOrFail();

        $validated['password'] = Hash::make($validated['password']);
        $validated['country'] = $country->name;
        $validated['role_id'] = $role->id;
        $validated['manager_id'] = $manager->id;

        $newUser = $this->userRepository->create($validated);


        if(isset($validated['profile_image']))
        {
            $image_path = $this->UploadProfilePicture($request);

            $validated['image'] = $image_path;
        }

        $userProfile = $newUser->profile()->create($validated);

        $userProfile->address()->create($validated);

        $newUser->service()->create();

        $user = $this->userRepository->get($newUser->id)->firstOrFail();

        return $user;
    }
}