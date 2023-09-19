<?php 

namespace App\Interfaces\Services;


interface IRoleService{

    public function storeRole($data);
    public function getRole(string $id);
    public function getAllRole();
    public function updateRole($request, $id);
    public function deleteRole(string $id);
}

