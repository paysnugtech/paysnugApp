<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IVerificationRepository;
use App\Models\Verification;

class VerificationRepository implements IVerificationRepository{


    public function create($data)
    {
        $user = Verification::create($data);
        return $user;
    }
    
    public function get(string $id){

        return Verification::with(['user'])->where('id', $id);
    }
    
    public function getAll(){

        return Verification::with(['user'])->get();
    }

    public function getByUserId($user_id)
    {
        return Verification::with(['user'])->where('user_id', $user_id);
    }
    
    public function update(Verification $verification, $data){
        
        $verification->update($data);

        return $verification;
    }
    
    public function delete(Verification $verification){

        return $verification->delete();
    }
    
}