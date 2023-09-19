<?php 

namespace App\Interfaces\Services;


interface IPasswordService{

    public function storeUser($data);
    public function getUser(string $id);
    public function getAllUser();
    public function processForgotPassword($request);
    public function resetPassword($data);
    public function updatePassword($request, string $id);
}

