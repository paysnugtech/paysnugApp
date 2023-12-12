<?php 


namespace App\Implementations\Services;


use App\Enums\TokenMethodEnum;
use App\Enums\TokenTypeEnum;
use App\Enums\VerificationEnum;
use App\Http\Resources\UsersResource;
use App\Interfaces\Repositories\IRegisterTokenRepository;
use App\Interfaces\Repositories\ITokenRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Services\ITokenService;
use App\Traits\EmailTrait;
use App\Traits\ResponseTrait;
use App\Traits\StatusTrait;
use App\Traits\TokenTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TokenService implements ITokenService
{
    use EmailTrait,
        ResponseTrait,
        StatusTrait,
        TokenTrait;
    

    protected $expire_in;
    protected $registerTokenRepository;
    protected $tokenRepository;
    protected $userRepository;
    

    public function __construct(
        IRegisterTokenRepository $registerTokenRepository,
        ITokenRepository $tokenRepository, 
        IUserRepository $userRepository,
    )
    {
        $this->expire_in = env('TOKEN_EXPIRE_IN');
        $this->registerTokenRepository = $registerTokenRepository;
        $this->tokenRepository = $tokenRepository;
        $this->userRepository = $userRepository;
    }
    
    
    
    public function storeFingerPrintToken(){

        $user = Auth::user();

        $oldToken = $this->tokenRepository->fetchByEmailType($user->email, TokenTypeEnum::FingerPrint->value)->first();

        if($oldToken)
        {

            // Token already sent and not expired
            $difference = Carbon::now()->diffInMinutes($oldToken->expires_at);

            if($difference > $this->expire_in)
            {
                return $this->errorResponse(
                    422, 
                    'Token already already sent, wait for '. $this->expire_in .' minutes to generate another token'
                );
            }
            else
            {
                // Delete expired token
                $this->tokenRepository->delete($oldToken);
            }
            
        }


        $token = random_int(101010, 999999);


        // Sent the token to email/phone
        /* $sendEmail = $this->setFingerPrintTokenEmail($user->email, $token);


        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                [], 
                $sendEmail->message
            );
        } */

        DB::beginTransaction();

        $this->tokenRepository->create([
            'id' => Str::uuid(),
            'email' => $user->email,
            'number' => $token,
            'type' => TokenTypeEnum::FingerPrint->value,
            'created_at' => now(),
        ]);

        DB::commit();


        return $this->successResponse( 
            message: 'Token sent Successful'
        );
    }
    
    


    public function storeRegistrationToken($request){

        $validated = $request->validated();

        $oldToken = $this->registerTokenRepository->get($validated['email'])->first();

        if($oldToken)
        {

            // Token already sent and not expired
            $difference = Carbon::now()->diffInMinutes($oldToken->created_at);

            if($difference > $this->expire_in)
            {
                return $this->errorResponse(
                    422, 
                    'Token already already sent, wait for '. $this->expire_in .' minutes to generate another token'
                );
            }
            else
            {
                // Delete expired token
                $this->registerTokenRepository->delete($oldToken);
            }
            
        }


        $data = [
            'email' => $validated['email'],
            'token' => random_int(101010, 999999),
            'created_at' => now()
        ];


        // Sent the token to email/phone
        $sendEmail = $this->registrationTokenEmail($validated['email'], $data['token']);


        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message
            );
        }

        DB::beginTransaction();

        $this->registerTokenRepository->create($data);

        DB::commit();


        return $this->successResponse( 
            message: 'Token sent Successful'
        );
    }
    
    
    
    public function storeRestPinToken($user, $request){

        $oldToken = $this->tokenRepository->fetchByEmailType($user->email, TokenTypeEnum::ResetPin->value)->first();

        if($oldToken)
        {


            if(!$this->TokenExpire($oldToken))
            {
                return $this->errorResponse(
                    $this->statusCode("token_sent"), 
                    'Token already already sent, wait for '. $this->expire_in .' minutes to generate another token',
                    []
                );
            }
            else
            {
                // Delete expired token
                $this->tokenRepository->delete($oldToken);
            }
            
        }


        $token = random_int(101010, 999999);


        // Sent the registration token to email/phone
        $sendEmail = $this->sendResetPinTokenEmail($user, $token);


        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message,
            );
        }

        DB::beginTransaction();

        $this->tokenRepository->create([
            'id' => Str::uuid(),
            'email' => $user->email,
            'number' => $token,
            'expire_in' => $this->expire_in,
            'type' => TokenTypeEnum::ResetPin->value,
            'created_at' => now(),
        ]);

        DB::commit();


        return $this->successResponse(
            message: 'Token sent Successful'
        );
    }
    
    
    
    public function storeChangePasswordToken($user, $request){


        $oldToken = $this->tokenRepository->fetchByEmailType($user->email, TokenTypeEnum::ChangePassword->value)->first();
        
        // print_r($oldToken);
        if($oldToken)
        {


            if(!$this->TokenExpire($oldToken))
            {
                return $this->errorResponse(
                    message: 'Token already already sent, wait for '. $oldToken->expire_in .' minutes to generate another token'
                );
            }
            else
            {
                // Delete expired token
                $this->tokenRepository->delete($oldToken);
            }
            
        }


        $token = random_int(101010, 999999);


        // Sent the registration token to email/phone
        $sendEmail = $this->sendChangePasswordTokenEmail($user, $token);


        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message
            );
        }

        DB::beginTransaction();

        $this->tokenRepository->create([
            'id' => Str::uuid(),
            'email' => $user->email,
            'number' => $token,
            'expire_in' => $this->expire_in,
            'type' => TokenTypeEnum::ChangePassword->value,
            'created_at' => now(),
        ]);

        DB::commit();


        return $this->successResponse( 
            message: 'Token sent Successful'
        );
    }
    
    
    
    public function storeResetPasswordToken($request){


        $validated  = $request->validated();

        $user = $this->userRepository->getByEmail($validated['email'])->firstOrFail();

        $oldToken = $this->tokenRepository->fetchByEmailType($user->email, TokenTypeEnum::ResetPassword->value)->first();

        if($oldToken)
        {


            if(!$this->TokenExpire($oldToken))
            {
                return $this->errorResponse(
                    message: 'Token already already sent, wait for '. $oldToken->expire_in .' minutes to generate another token'
                );
            }
            else
            {
                // Delete expired token
                $this->tokenRepository->delete($oldToken);
            }
            
        }


        $token = random_int(101010, 999999);


        // Sent the registration token to email/phone
        $sendEmail = $this->sendResetPasswordTokenEmail($user, $token);


        if(!$sendEmail->success)
        {
            return $this->errorResponse(
                message: $sendEmail->message
            );
        }

        DB::beginTransaction();

        $this->tokenRepository->create([
            'id' => Str::uuid(),
            'email' => $user->email,
            'number' => $token,
            'expire_in' => $this->expire_in,
            'type' => TokenTypeEnum::ResetPassword->value,
            'created_at' => now(),
        ]);

        DB::commit();


        return $this->successResponse( 
            message: 'Token sent Successful'
        );
    }
    
    
    
    public function storeToken($request)
    {
        $validated = $request->validated();
        $method = $validated['method'];
        $user_name = $validated['user_name'];

        if($method == TokenMethodEnum::Email->value)
        {
            if (!filter_var($user_name, FILTER_VALIDATE_EMAIL)) 
            {
                return $this->errorResponse(
                    422,
                    "Verification error",
                    [
                        "email" => "Invalid Email"
                    ]
                );
            }
        }
        else
        {

            print_r($method);

        }
    }
    
    
    
    public function verifyFingerPrintToken($request){

        $validated = $request->validated();

        $user = Auth::user();

        $oldToken = $this->tokenRepository->fetchByEmailNumberType($user->email, $validated['token'], TokenTypeEnum::FingerPrint->value)->first();

        if(!$oldToken)
        {
            return $this->errorResponse(
                422, 
                'Invalid Token'
            );
            
        }


        // Token already sent and not expired
        $difference = Carbon::now()->diffInMinutes($oldToken->expires_at);

        if($difference > $this->expire_in)
        {
            return $this->errorResponse(
                422, 
                'Token already expired'
            );
        }


        DB::beginTransaction();

        // Delete expired token
        $this->tokenRepository->delete($oldToken);

        DB::commit();


        return $this->successResponse(
            message: 'Token verify Successful'
        );
    }
    
    
    
    public function verifyPinToken($request){

        $validated = $request->validated();

        $user = Auth::user();

        $oldToken = $this->tokenRepository->fetchByEmailNumberType($user->email, $validated['token'], TokenTypeEnum::ChangePin->value)->first();

        if(!$oldToken)
        {
            return $this->errorResponse(
                422, 
                'Invalid Token',
            );
            
        }


        // Token already sent and not expired
        $difference = Carbon::now()->diffInMinutes($oldToken->expires_at);

        if($difference > $this->expire_in)
        {
            return $this->errorResponse(
                422, 
                'Token already expired',
            );
        }


        DB::beginTransaction();

        // Delete expired token
        $this->tokenRepository->delete($oldToken);

        DB::commit();


        return $this->successResponse(
            message: 'Token verify Successful'
        );
    }



    
    /*  
    */
    protected function storeVerification($user)
    {

        // Save Notification
        $verification = $this->verificationRepository->create([
            'user_id' => $user->id,
            'phone_verified' => VerificationEnum::Verified->value,
        ]);


        $verification->bill()->create([
            'user_id' => $user->id
        ]);

        $verification->bvn()->create([
            'user_id' => $user->id
        ]);

        $verification->card()->create([
            'user_id' => $user->id
        ]);

        return $verification; 
    }

    

    public function verifyRegistrationToken($request){

        $validated = $request->validated();

        $token = $this->registerTokenRepository->get($validated['email'])->firstOrFail();


        if($token->token != $validated['token'])
        {

            return $this->errorResponse(
                422, 
                'Invalid token.'
            );
            
        }


        // Token already sent and not expired
        $difference = Carbon::now()->diffInMinutes($token->created_at);

        if($difference > $this->expire_in)
        {

            return $this->errorResponse(
                422, 
                'Token already expired.'
            );
            
        }

        DB::beginTransaction();
        
        $this->registerTokenRepository->delete($token);

        DB::commit();

        return $this->successResponse(
            message: 'Token verify successful'
        );
    }
    
    
}