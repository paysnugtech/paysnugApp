<?php 

namespace App\Interfaces\Services;


interface IUserService{

    
    public function getUser(string $id);
    public function getAllUser();
    public function getWallet($user);
    public function storeFingerPrint($request, $user);
    public function changePin($request, $user);
    public function resetPin($request, $user);
    public function setPin($request, $user);
    public function updateNotification($request, $user);
    public function updateProfile($request, $user);
    public function updateUser($request, $user);
    public function deleteUser($user);
}

