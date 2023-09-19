<?php

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePinRequest;
use App\Http\Requests\StoreFingerPrintRequest;
use App\Http\Requests\StorePinRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Interfaces\Services\IUserService;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    protected $userService;


    function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->userService->getAllUser();

        return $response;
    }

    public function fingerPrint(StoreFingerPrintRequest $request, string $id){
        
        $response = $this->userService->storeFingerPrint($request, $id);
        
        return $response;

    }

    public function notification(UpdateNotificationRequest $request, string $id)
    {
        $response = $this->userService->updateNotification($request, $id);
        
        return $response;
    }

    public function pin(StorePinRequest $request, string $id){
        
        $response = $this->userService->setPin($request, $id);
        
        return $response;

    }

    public function profile(UpdateProfileRequest $request, string $id){

        $response = $this->userService->updateProfile($request, $id);
        
        return $response;

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        
        $response = $this->userService->storeUser($request);
        
        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $response = $this->userService->getUser($id);
        
        return $response;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        
        $response = $this->userService->updateUser($request, $id);
        
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $response = $this->userService->deleteUser($id);
        
        return $response;
    }
}
