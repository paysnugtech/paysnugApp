<?php 

namespace App\Traits;

use App\Interfaces\Repositories\IAccountRepository;
use App\Models\Account;
use App\Traits\StatusCode;
use App\Traits\WhereHouseTrait;

trait AccountTrait{



    
    /*  
    */
    protected function createAccount($wallet, $walletType = "intra")
    {


        if($wallet->country->currency_code == "NGN")
        {
            $appWalletId = env('PSG_NGN_DEFAULT_WALLET');

            if($walletType == "intra")
            {
                $data = [
                    "account_holder_name" => $wallet->user->profile->other_name." ". $wallet->user->profile->first_name,
                    "phone_number" => $wallet->user->profile->phone_no,
                    "email" => $wallet->user->email,
                    "is_intra" => true,
                    "is_fixed" => false,
                    "wallet_id" => $appWalletId
                ];

            }
            else
            {
                $provider_id = "9a27c77a-f1d8-409f-9d17-6b6ce75c2800";

                $data = [
                    "account_holder_name" => $wallet->user->profile->other_name." ". $wallet->user->profile->first_name,
                    "phone_number" => $wallet->user->profile->phone_no,
                    "email" => $wallet->user->email,
                    "is_intra" => false,
                    "is_fixed" => false,
                    "provider_id" => $provider_id,
                    "wallet_id" => $appWalletId
                ];
            }
        }

        
        $newAccount = $this->curlPostApi($data);

        return $newAccount;
    }

    
    /*  
    */
    protected function createSnugAccount($wallet)
    {

        $appWalletId = env('PSG_NGN_DEFAULT_WALLET');

        $profile = $wallet->user->profile;

        $request = [
            "data" => json_encode([
                "first_name" => $profile->first_name,
                "last_name" => $profile->other_name,
                "dob" => $profile->dob,
                "phone_number" => $profile->phone_no,
                "email" => $wallet->user->email,
                "is_intra" => true,
                "is_fixed" => false,
                "wallet_id" => $appWalletId
            ]),
            "header" => [
                'X-Secret-Key: '. env('PSG_SECRET_KEY'),
                'Accept: application/json',
                'Content-Type: application/json'
            ],
            "url" => env('INDIVIDUAL_ACCOUNT_ENDPOINT')
        ];

        
        $newAccount = $this->curlPostApi($request);

        return $newAccount;
    }

    
    /*  
    */
    protected function getAccountFromNo($phoneNumber)
    {
        
        // Check if the first character is a zero
        if (substr($phoneNumber, 0, 1) === '0') 
        {
            
            $phoneNumber = substr($phoneNumber, 1);
        }

        
        return $phoneNumber;

    }
}