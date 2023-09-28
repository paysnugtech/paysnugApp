<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IDeviceRepository;
use App\Models\Device;

class DeviceRepository implements IDeviceRepository{


    public function create($data){

        $device = Device::create($data);
        return $device;
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
        return Device::with([
            'user'
        ])->where('user_id', $user_id);
    }

    public function save(Device $device)
    {

        $obj = $device->save();

        return $obj;
    }
    
    public function update(Device $device, $data){
        
        $device->update($data);

        return $device;
    }
    
    public function delete(Device $device){

        return $device->delete();
    }
    
}