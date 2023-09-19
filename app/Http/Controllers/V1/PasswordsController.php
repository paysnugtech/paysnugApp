<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Interfaces\Repositories\IPasswordResetRepository;
use App\Interfaces\Services\IPasswordService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;

class PasswordsController extends Controller
{
    use ErrorResponse, SuccessResponse;

    protected $passwordResetRepository;
    protected $passwordService;


    public function __construct(IPasswordResetRepository $passwordResetRepository, IPasswordService $passwordService)
    {
        $this->passwordResetRepository = $passwordResetRepository;
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
        $validated = $request->validated();

        $data = $this->passwordResetRepository->getByEmailAndToken($validated);

        if(!$data->exists())
        {
            return $this->errorResponse(
                [
                    'token' => 'Token is not valid'
                ], 
                'Token validation error',
                422
            );
        }

        $difference = Carbon::now()->diffInSeconds($data->first()->created_at);

        if($difference > 3600)
        {
            return $this->errorResponse(
                [
                    'token' => 'Token Expired',
                ], 
                'Token validation error',
                400
            );
        }


        $reset = $this->passwordService->resetPassword($validated);

        return $reset;
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
    public function update(UpdatePasswordRequest $request, string $id)
    {
        $update = $this->passwordService->updatePassword($request, $id);

        return $update;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
