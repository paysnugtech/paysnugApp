<?php 

namespace App\Interfaces\Services;


interface IRegisterService{
    
    public function storeRegistrationToken($request);
    public function storeUser($request);
    public function verifyRegistrationToken($request);

}

