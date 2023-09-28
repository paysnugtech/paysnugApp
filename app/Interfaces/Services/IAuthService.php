<?php 

namespace App\Interfaces\Services;


interface IAuthService{

    public function loginToken($request);
    public function loginUser($request);
    public function logoutUser();
    public function refreshToken();
    public function payload();
}

