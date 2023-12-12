<?php 

namespace App\Traits;

use Illuminate\Support\Facades\Hash;

trait TokenTrait{
    
    protected function TokenExpire($token)
    {

        $expire_time = now()->subMinutes($token->expire_in);

        if($expire_time > $token->created_at)
        {

            return true;
            
        }

        return false;
    }



    protected function VerifyToken($text, $object)
    {

        if(!Hash::check($text, $object->token))
        {
            return false;
        }

        return true;
    }



    
}