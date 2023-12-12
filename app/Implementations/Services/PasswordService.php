<?php 


namespace App\Implementations\Services;

use App\Enums\TokenTypeEnum;
use App\Interfaces\Repositories\IPasswordResetRepository;
use App\Interfaces\Repositories\ITokenRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Services\IPasswordService;
use App\Models\User;
use App\Traits\EmailTrait;
use App\Traits\ResponseTrait;
use App\Traits\StatusTrait;
use App\Traits\TokenTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PasswordService implements IPasswordService
{
    use EmailTrait, 
        ResponseTrait,
        StatusTrait,
        TokenTrait;


    protected $expires_in;
    protected $passwordResetRepository;
    protected $tokenRepository;
    protected $userRepository;

    public function __construct(
        IPasswordResetRepository $passwordResetRepository, 
        ITokenRepository $tokenRepository, 
        IUserRepository $userRepository){

        $this->expires_in = env('TOKEN_EXPIRES');
        $this->passwordResetRepository = $passwordResetRepository;
        $this->tokenRepository = $tokenRepository;
        $this->userRepository = $userRepository;
    }
    
    
    public function storeUser($data)
    {

    }
    
    public function getUser(string $id)
    {

    }
    
    public function getAllUser()
    {

    }
    
    public function processForgotPassword($request)
    {
        $validated = $request->validated();
        
        // $token = Str::random(64);
        $token = random_int(101010, 999999);
        $created_at = now();

        $obj = $this->passwordResetRepository->getByEmail($validated['email']);
        
        if($obj->exists())
        {
            // print_r($obj->first());

            // update into the database
            $this->passwordResetRepository->delete($obj->first());

            $validated['token'] = $token;
            $validated['created_at'] = $created_at;

            // print_r($validated);

            // update into the database
            $this->passwordResetRepository->create($validated);

            $data = [
                'email' => $validated['email']
            ];
            
            return $this->successResponse($data, 
                "New Password Reset token sent successful"
            );
        }


        $validated['token'] = $token;
        $validated['created_at'] = $created_at;


        // Insert into the database
        $create = $this->passwordResetRepository->create($validated);


        if(!$create)
        {
            return $this->errorResponse([], "Something went wrong, Try again");
        }


        //Send token email
        

        $data = [
            'email' => $validated['email']
        ];

        return $this->successResponse($data, 
            "Password Reset token sent successful"
        );

    }
    


    public function changePassword($user, $request)
    {
        $validated = $request->validated();
        
        $user->password = bcrypt($validated['password']);
        
        DB::beginTransaction();
        
            $update = $this->userRepository->store($user);

            if(!$update)
            {
                return $this->errorResponse(
                    message: 'Unable to Change password!'
                );
            }

        DB::commit();


        // Sent the registration token to email/phone
        $sendEmail = $this->sendChangePasswordSuccessEmail($user);


        if(!$sendEmail->success)
        {
            /* return $this->errorResponse(
                message: $sendEmail->message
            ); */
        }

        return $this->successResponse(
            message: 'Password Changed Successful'
        );
    } 
    
    public function resetPassword($request)
    {
        
        $validated = $request->validated();
        $email = $validated['email'];
        $token = $validated['token'];
        $type = TokenTypeEnum::ResetPassword->value;

        $tokenObj = $this->tokenRepository->fetchByEmailNumberType($email, $token, $type)->first();

        if(!$tokenObj)
        {
            return $this->errorResponse(
                422, 
                'Not found error'
            );
        }
        

        if($this->TokenExpire($tokenObj))
        {
            return $this->errorResponse(
                $this->StatusCode('token_expired'),
                'Token validation error',
                [
                    'token' => 'Token Expired',
                ], 
            );
        }

        

        $user = $this->userRepository->getByEmail($validated['email'])->firstOrFail();
        $user->password = bcrypt($validated['password']);

        DB::beginTransaction();

            $updated = $this->userRepository->store($user);

            if(!$updated)
            {
                return $this->errorResponse(
                    message: 'Unable to update password',
                );
            }

            $this->tokenRepository->delete($tokenObj);

        DB::commit();



        // Sent the registration token to email/phone
        $sendEmail = $this->sendResetPasswordSuccessEmail($user);


        if(!$sendEmail->success)
        {
            /* return $this->errorResponse(
                message: $sendEmail->message
            ); */
        }

        return $this->successResponse(
            message: 'Reset Successful'
        );
    }
}