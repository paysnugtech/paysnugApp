<?php 

namespace App\Interfaces\Services;


interface ITokenService{
    
    public function storeChangePasswordToken($user, $request);
    public function storeRegistrationToken($request);
    public function storeFingerPrintToken();
    public function storeResetPasswordToken($request);
    public function storeRestPinToken($user, $request);
    public function storeToken($request);
    public function verifyFingerPrintToken($request);
    public function verifyPinToken($request);
    public function verifyRegistrationToken($request);

}

