<?php 

namespace App\Interfaces\Services;



interface IManagerService{

    public function storeManager($data);
    public function getManager(string $id);
    public function getAllManager();
    public function updateManager($request, $id);
    public function deleteManager(string $id);
}

