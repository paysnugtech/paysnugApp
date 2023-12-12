<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IManagerRepository;
use App\Models\Manager;

class ManagerRepository implements IManagerRepository{


    public function create($data){

        return Manager::create($data);
    }
    
    public function get(string $id){

        return Manager::findOrFail($id);
    }
    
    public function getAll(){

        return Manager::all();
    }

    public function getRandomManager()
    {
        return Manager::select("*")->inRandomOrder();
    }

    
    public function store(Manager $manager)
    {

        $obj = $manager->save();
        
        return $obj;
    }

    
    public function update(Manager $manager, $data){
        
        $manager->update($data);

        return $manager;
    }
    
    public function delete(Manager $manager){

        return $manager->delete();
    }
    
}