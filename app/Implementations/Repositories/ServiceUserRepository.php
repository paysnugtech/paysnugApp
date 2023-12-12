<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\IServiceUserRepository;
use App\Models\ServiceUser;

class ServiceUserRepository implements IServiceUserRepository{


    public function create($data){
        
        $serviceUser = ServiceUser::create($data);
        return $serviceUser;
    }
    
    public function get(string $id){

        return ServiceUser::where('id', $id);
    }
    
    public function getAll(){

        return ServiceUser::all();
    }


    public function getByServiceId($service_id)
    {
        return ServiceUser::where('service_id', $service_id);
    }


    public function getByUserId($user_id)
    {
        return ServiceUser::where('user_id', $user_id);
    }

    

    public function store(ServiceUser $serviceUser)
    {

        $save = $serviceUser->save();

        return $save;
    }
    
    public function update(ServiceUser $serviceUser, $data)
    {
        
        $update = $serviceUser->update($data);

        return $update;
    }
    
    public function delete(ServiceUser $serviceUser)
    {

        $delete = $serviceUser->delete();

        return $delete;
    }
    
}