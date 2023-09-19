<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\IServiceRepository;
use App\Models\Service;

class ServiceRepository implements IServiceRepository{


    public function create($data){
        
        $service = Service::create($data);
        return $service;
    }
    
    public function get(string $id){

        return Service::with(['address'])->where('id', $id);
    }
    
    public function getAll(){

        return Service::with(['address'])->get();
    }

    public function getByUserId($user_id)
    {
        return Service::with(['address'])->where('user_id', $user_id);
    }
    
    public function update(Service $service, $data){
        
        $service->update($data);

        return $service;
    }
    
    public function delete(Service $service){

        return $service->delete();
    }
    
}