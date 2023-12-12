<?php 

namespace App\Traits;


use App\Enums\AuthMethodEnum;
use Illuminate\Support\Facades\Hash;

trait AuthTrait{


    
    /*  
    */
    protected function verifyUser($user, $auth, $auth_method)
    {


        if($auth_method === AuthMethodEnum::Pin->value)
        {
            if(!$this->verifyPin($user, $auth))
            {
                $this->errorResponse = $this->errorResponse(
                    code: 422,
                    message: "User authentication error",
                    errors: [
                        'pin' => "wrong pin"
                    ]
                );

                return false;
            }
        }
        else
        {
            if(!$this->verifyFingerPrint($user, $auth))
            {
                $this->errorResponse = $this->errorResponse(
                    code: 422,
                    message: "User authentication error",
                    errors: [
                        'biometric' => "invalid biometric"
                    ]);

                return false;
            }
        }


        return true;
    }


    
    /*  
    */
    protected function verifyFingerPrint($user, $auth)
    {

        $flag = true;

        if (!Hash::check($auth, $user->finger_print)) {
            $flag = false;
        }

        return $flag;
        
    }
    


    /*  
    */
    protected function verifyPin($user, $auth)
    {

        $flag = true;

        if (!Hash::check($auth, $user->pin)) {
            $flag = false;
        }

        return $flag;
    }

    
}