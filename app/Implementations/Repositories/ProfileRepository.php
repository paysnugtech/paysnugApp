<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\IProfileRepository;
use App\Models\Profile;

class ProfileRepository implements IProfileRepository{


    public function create($data){
        
        $profile = Profile::create($data);
        return $profile;
    }
    
    public function get(string $id){

        return Profile::with(['address'])->where('id', $id);
    }
    
    public function getAll(){

        return Profile::with(['address'])->get();
    }

    public function getByPhoneNo($phone_no)
    {
        return Profile::with(['address'])->where('phone_no', $phone_no);
    }

    public function getByUserId($user_id)
    {
        return Profile::with(['address'])->where('user_id', $user_id);
    }
    
    public function update(Profile $profile, $data){
        
        $profile->update($data);

        return $profile;
    }
    
    public function delete(Profile $profile){

        return $profile->delete();
    }
    
}