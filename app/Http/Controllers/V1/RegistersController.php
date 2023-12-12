<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTokenRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\VerifyRegisterTokenRequest;
use App\Interfaces\Services\IRegisterService;
use Illuminate\Http\Request;

class RegistersController extends Controller
{

    protected $registerService;


    public function __construct(IRegisterService $registerService)
    {
        $this->registerService = $registerService;
    }


    //
    public function store(StoreUserRequest $request)
    {
        $response = $this->registerService->storeUser($request);

        return $response;
    }


    //
    public function token(RegisterTokenRequest $request)
    {
        $response = $this->registerService->storeRegistrationToken($request);

        return $response;
    }


    //
    public function verifyToken(VerifyRegisterTokenRequest $request)
    {
        $response = $this->registerService->verifyRegistrationToken($request);

        return $response;
    }



    

}
