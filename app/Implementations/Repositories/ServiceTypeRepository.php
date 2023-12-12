<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\IServiceTypeRepository;
use App\Models\ServiceType;

class ServiceTypeRepository implements IServiceTypeRepository{


    public function create($data){
        
        $serviceType = ServiceType::create($data);
        return $serviceType;
    }
    
    public function get(string $id){

        return ServiceType::where('id', $id);
    }
    
    public function getAll(){

        return ServiceType::all();
    }

    public function getByName($name)
    {
        return ServiceType::where('name', $name);
    }

    public function getChargesByName($name)
    {
        $serviceType = ServiceType::where('name', $name)->firstOrFail();

        return $serviceType->charges;
    }

    public function getDiscountByName($name)
    {
        $serviceType = ServiceType::where('name', $name)->firstOrFail();

        return $serviceType->discount;
    }

    public function getFeeByName($name)
    {
        $serviceType = ServiceType::where('name', $name)->firstOrFail();

        return $serviceType->fee;
    }

    public function store(ServiceType $serviceType)
    {

        $save = $serviceType->save();

        return $save;
    }
    
    public function update(ServiceType $serviceType, $data)
    {
        
        $update = $serviceType->update($data);

        return $update;
    }
    
    public function delete(ServiceType $serviceType)
    {

        $delete = $serviceType->delete();

        return $delete;
    }
    
}