<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IVerificationRepository;
use App\Models\Verification;

class VerificationRepository implements IVerificationRepository{


    public function create($data)
    {
        $obj = Verification::create($data);

        return $obj;
    }
    
    
    public function get(string $id){

        return Verification::with(['bill', 'bvn', 'card', 'user'])->where('id', $id);
    }
    

    public function getAll(){

        return Verification::with(['bill', 'bvn', 'card', 'user'])->get();
    }


    public function getByUserId($user_id)
    {
        return Verification::with(['bill', 'bvn', 'card', 'user'])->where('user_id', $user_id);
    }


    public function store(Verification $obj)
    {

        $save = $obj->save();

        return $save;
    }
    

    public function update(Verification $obj, $data){
        
        $obj->update($data);

        return $obj;
    }
    

    public function delete(Verification $obj){

        return $obj->delete();
    }
    
}