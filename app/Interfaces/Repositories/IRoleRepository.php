<?php 

namespace App\Interfaces\Repositories;


use App\Models\Role;



interface IRoleRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByName($name);
    public function update(Role $role, $data);
    public function delete(Role $role);

}

