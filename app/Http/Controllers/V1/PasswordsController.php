<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Interfaces\Repositories\IPasswordResetRepository;
use App\Interfaces\Services\IPasswordService;
use Illuminate\Http\Request;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;

class PasswordsController extends Controller
{

    protected $passwordService;


    public function __construct(IPasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * generate password reset token.
     */
    public function forgot(ForgotPasswordRequest $request)
    {
        $create = $this->passwordService->processForgotPassword($request);

        return $create;
    }



    /**
     * Store a newly created password in storage.
     */
    public function reset(ResetPasswordRequest $request)
    {

        $response = $this->passwordService->resetPassword($request);

        return $response;
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function change(ChangePasswordRequest $request)
    {
        $user = auth()->user();

        $response = $this->passwordService->changePassword($user, $request);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
