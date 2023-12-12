<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IDeviceTokenRepository;
use App\Interfaces\Repositories\IRegisterTokenRepository;
use App\Models\RegisterToken;
use Illuminate\Support\Facades\DB;

class RegisterTokenRepository implements IRegisterTokenRepository{


    public function create($data)
    {
        $obj = DB::table('register_verification_tokens')->insert($data);

        return $obj;
    }
    
    public function get(string $email){

        return RegisterToken::with([])->where('email', $email);
    }
    
    public function getAll(){

        return RegisterToken::with([])->get();
    }

    public function store(RegisterToken $obj)
    {

        $save = $obj->save();

        return $save;
    }

    public function update(RegisterToken $obj, $data){
        
        $update = $obj->update($data);

        return $update;
    }
    
    public function delete(RegisterToken $obj){

        $delete = $obj->delete();

        return $delete;
    }
    
}