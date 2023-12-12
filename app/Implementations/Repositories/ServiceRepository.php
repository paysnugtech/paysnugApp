<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\IServiceRepository;
use App\Models\Service;

class ServiceRepository implements IServiceRepository{


    public function create($data){
        
        $obj = Service::create($data);
        return $obj;
    }
    
    public function fetch(string $id){

        return Service::with(['user'])->where('id', $id);
    }
    
    public function fetchAll(){

        return Service::with(['user'])->get();
    }

    public function fetchByName($name)
    {
        $service = Service::where('name', $name);

        return $service;
    }


    public function fetchByUserId($user_id)
    {
        return Service::with(['user'])->where('user_id', $user_id);
    }


    public function fetchChargesByName($name)
    {
        $service = Service::where('name', $name)->firstOrFail();

        return $service->charges;
    }

    public function fetchDiscountByName($name)
    {
        $service = Service::where('name', $name)->firstOrFail();

        return $service->discount;
    }

    public function fetchFeeByName($name)
    {
        $service = Service::where('name', $name)->firstOrFail();

        return $service->fee;
    }


    public function store(Service $obj)
    {

        $save = $obj->save();

        return $save;
    }
    
    public function update(Service $obj, $data)
    {
        
        $update = $obj->update($data);

        return $update;
    }
    
    public function delete(Service $service)
    {

        $delete = $service->delete();

        return $delete;
    }
    
}