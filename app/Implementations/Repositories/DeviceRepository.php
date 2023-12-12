<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IDeviceRepository;
use App\Models\Device;

class DeviceRepository implements IDeviceRepository{


    public function create($data){

        $obj = Device::create($data);
        return $obj;
    }
    
    public function get(string $id){

        return Device::with([
            'user'
        ])->where('id', $id);
    }
    
    public function getAll(){

        return Device::with([
            'user'
        ])->get();
    }

    public function getByUserId($user_id)
    {
        
        $obj = Device::with([
            'user'
        ])->where('user_id', $user_id);

        return $obj;
    }

    public function store(Device $device)
    {

        $obj = $device->save();

        return $obj;
    }
    
    public function update(Device $obj, $data){
        
        $obj->update($data);

        return $obj;
    }
    
    public function delete(Device $obj){

        return $obj->delete();
    }
    
}