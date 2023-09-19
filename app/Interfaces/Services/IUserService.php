<?php 

namespace App\Interfaces\Services;


interface IUserService{

    
    public function getUser(string $id);
    public function getAllUser();
    public function storeFingerPrint($request, $id);
    public function setPin($request, $id);
    public function storeUser($data);
    public function updateNotification($request, $id);
    public function updateProfile($request, $id);
    public function updateUser($request, $id);
    public function deleteUser(string $id);
}

