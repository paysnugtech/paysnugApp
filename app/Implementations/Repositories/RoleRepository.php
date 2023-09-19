<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IRoleRepository;
use App\Models\Role;

class RoleRepository implements IRoleRepository{


    public function create($data){
    
        return Role::create($data);
    }
    
    public function get(string $id){

        return Role::findOrFail($id);
    }
    
    public function getAll(){

        return Role::all();
    }

    public function getByName($name)
    {
        return Role::where('name', $name);
    }
    
    public function update(Role $role, $data){
        
        $role->update($data);

        return $role;
    }
    
    public function delete(Role $role){

        return $role->delete();
    }
    
}