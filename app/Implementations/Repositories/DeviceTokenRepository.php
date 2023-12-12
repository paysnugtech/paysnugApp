<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IDeviceTokenRepository;
use App\Models\DeviceToken;
use Illuminate\Support\Facades\DB;

class DeviceTokenRepository implements IDeviceTokenRepository{


    public function create($data)
    {
        // $create = DB::table('device_verification_tokens')->insert($data);
        $obj = DeviceToken::create($data);

        return $obj;
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

    public function store(DeviceToken $obj)
    {

        $save = $obj->save();

        return $save;
    }

    public function update(DeviceToken $obj, $data){
        
        $update = $obj->update($data);

        return $update;
    }
    
    public function delete(DeviceToken $obj){

        return $obj->delete();
    }
    
}