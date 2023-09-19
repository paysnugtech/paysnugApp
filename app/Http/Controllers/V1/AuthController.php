<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\LoginUserRequest;
use App\Http\Controllers\Controller;
use App\Interfaces\Services\IAuthService;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;
use App\Traits\TokenResponse;

class AuthController extends Controller
{
    use ErrorResponse, SuccessResponse, TokenResponse;

    protected $authService;

    public function __construct(IAuthService $authService)
    {
        // $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->authService = $authService;
    }

    public function login(LoginUserRequest $request)
    {
        $response = $this->authService->loginUser($request);

        return $response;
    }


    
    public function logout()
    {
        $response = $this->authService->logoutUser();

        return $response;
    }


    public function refresh()
    {
        $response = $this->authService->refreshToken();

        return $response;
    }



    public function payload()
    {
        $response = $this->authService->payload();

        return $response;
    }
}
