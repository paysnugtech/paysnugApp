<?php 


namespace App\Implementations\Services;

use App\Http\Resources\UsersResource;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Services\IAuthService;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;
use App\Traits\TokenResponse;
use Illuminate\Support\Facades\Auth;


class AuthService implements IAuthService
{
    use ErrorResponse, SuccessResponse, TokenResponse;

    protected $userRepository;

    public function __construct( IUserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }
    
    public function loginUser($request)
    {

        $credentials = $request->validated();

        $token = Auth::attempt($credentials);
        
        if (!$token) {

            return $this->errorResponse([], 'Invalid email or password!', 401);
        }

        $user = Auth::user();


        return $this->tokenResponse(
            new UsersResource($user),
            $token,
            'Successful'
        );
    }
    
    public function logoutUser()
    {
        Auth::logout();

        return $this->successResponse([], 'Successfully logged out');
    }
    
    public function refreshToken()
    {
        
        $user = Auth::user();
        $token = Auth::refresh();

        return $this->tokenResponse(
            new UsersResource($user),
            $token,
            'Successful'
        );
    }
    
    public function payload()
    {
        return Auth::payload();
    }
    
    
}