<?php

namespace App\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePinRequest;
use App\Http\Requests\ResetPinRequest;
use App\Http\Requests\StoreFingerPrintRequest;
use App\Http\Requests\StorePinRequest;
use App\Http\Requests\StoreProfilePictureRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Interfaces\Services\IUserService;
use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


    public function notification(UpdateNotificationRequest $request)
    {
        $user = Auth::user();

        $response = $this->userService->updateNotification($request, $user);
        
        return $response;
    }


    public function changePin(ChangePinRequest $request)
    {
        
        $user  = Auth::user();

        $response = $this->userService->changePin($request, $user);
        
        return $response;
    }


    public function pin(StorePinRequest $request){
        
        $user  = Auth::user();

        $response = $this->userService->setPin($request, $user);
        
        return $response;

    }


    public function storeFingerPrint(StoreFingerPrintRequest $request){

        $user  = Auth::user();
        
        $response = $this->userService->storeFingerPrint($request, $user);
        
        return $response;

    }


    public function resetPin(ResetPinRequest $request){
        
        $user  = Auth::user();

        $response = $this->userService->resetPin($request, $user);
        
        return $response;

    }


    public function profile(UpdateProfileRequest $request){

        $user = Auth::user();

        $response = $this->userService->updateProfile($request, $user);
        
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
    public function update(UpdateUserRequest $request)
    {
        $user = Auth::user();

        $response = $this->userService->updateUser($request, $user);
        
        return $response;
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $user = Auth::user();

        $response = $this->userService->deleteUser($user);
        
        return $response;
    }


    /**
     * Display a listing of the resource.
     */
    public function wallets()
    {
        $user = Auth::user();

        $response = $this->userService->getWallet($user);

        return $response;
    }
}
