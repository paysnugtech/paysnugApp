<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTokenRequest;
use App\Http\Requests\StorePasswordResetTokenRequest;
use App\Http\Requests\StoreResetTokenRequest;
use App\Http\Requests\StoreTokenRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\VerifyFingerPrintTokenRequest;
use App\Http\Requests\VerifyPinTokenRequest;
use App\Http\Requests\VerifyRegisterTokenRequest;
use App\Interfaces\Services\IRegisterService;
use App\Interfaces\Services\ITokenService;
use Illuminate\Http\Request;

class TokensController extends Controller
{

    protected $tokenService;


    public function __construct(ITokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }



    public function fingerPrintToken()
    {
        
        $response = $this->tokenService->storeFingerPrintToken();

        return $response;
    }



    public function generateToken(StoreTokenRequest $request)
    {

        $response = $this->tokenService->storeToken($request);

        return $response;
    } 



    public function storeRestPinToken(Request $request)
    {
        $user = auth()->user();

        $response = $this->tokenService->storeRestPinToken($user, $request);

        return $response;
    } 


    public function storeChangePasswordToken(Request $request)
    {
        $user = auth()->user();

        $response = $this->tokenService->storeChangePasswordToken($user, $request);

        return $response;
    }


    public function storeResetPasswordToken(StorePasswordResetTokenRequest $request)
    {

        $response = $this->tokenService->storeResetPasswordToken($request);

        return $response;
    }



    public function verifyFingerPrintToken(VerifyFingerPrintTokenRequest $request)
    {
        
        $response = $this->tokenService->verifyFingerPrintToken($request);

        return $response;
    } 



    public function verifyPinToken(VerifyPinTokenRequest $request)
    {
        
        $response = $this->tokenService->verifyPinToken($request);

        return $response;
    } 



    //
    /* public function store(StoreUserRequest $request)
    {
        $response = $this->registerService->storeUser($request);

        return $response;
    } */


    //
    /* public function token(RegisterTokenRequest $request)
    {
        $response = $this->registerService->storeRegistrationToken($request);

        return $response;
    } */


    //
    /* public function verifyToken(VerifyRegisterTokenRequest $request)
    {
        $response = $this->registerService->verifyRegistrationToken($request);

        return $response;
    } */



    

}
