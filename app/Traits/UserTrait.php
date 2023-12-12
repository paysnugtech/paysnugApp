<?php 

namespace App\Traits;


use App\Models\Level;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait UserTrait{

   

    protected function CreateUserObject($validated)
    {

        
        // get a role for the user
        $role = $this->roleRepository->getByName('User')->firstOrFail();

        // get a manager for the user
        $manager = $this->managerRepository->getRandomManager()->firstOrFail();

        // user
        $userObj = new User;
        $userObj->id = Str::uuid();
        $userObj->email = $validated['email'];
        $userObj->password = Hash::make($validated['password']);
        $userObj->pin = Hash::make($validated['pin']);
        $userObj->role_id = $role->id;
        $userObj->manager_id = $manager->id;

        return $userObj;
    }



    protected function CreateUserLevelObject($user)
    {

        
        $level = new Level;
        $level->code = 1;
        $level->daily_limit = 50000;
        $level->daily_unused = 50000;
        $level->monthly_limit = 300000;
        $level->monthly_unused = 300000;
        $level->user_id = $user->id;

        return $level;
    }



    protected function LoginUser($credentials)
    {
        $auth = Auth::attempt($credentials);
        
        if (!$auth) {

            return false;
        }


        return Auth::user();
    }
}