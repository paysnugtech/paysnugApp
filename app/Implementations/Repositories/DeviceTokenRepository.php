<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IDeviceTokenRepository;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\DB;

class DeviceTokenRepository implements IDeviceTokenRepository{


    public function create($data)
    {
        // $create = DB::table('device_verification_tokens')->insert($data);
        $create = DeviceToken::create($data);

        return $create;
    }
    
    public function get(string $id){

        return DeviceToken::with([])->where('id', $id);
    }
    
    public function getAll(){

        return DeviceToken::with([])->get();
    }

    public function getByEmail($email)
    {
        return DeviceToken::with([])->where('email', $email);
    }

    public function save(DeviceToken $deviceToken)
    {

        $saved = $deviceToken->save();

        return $saved;
    }

    public function update(DeviceToken $token, $data){
        
        $token->update($data);

        return $token;
    }
    
    public function delete(DeviceToken $token){

        return $token->delete();
    }
    
}