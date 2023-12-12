<?php 

namespace App\Traits;


use App\Interfaces\Repositories\IWalletRepository;
use App\Models\Wallet;

trait WalletTrait{


    protected $walletRepository;


    public function __construct(
        IWalletRepository $walletRepository
    )
    {
        $this->walletRepository = $walletRepository;
    }

    
    
    
    public function checkWallet($wallet){

        if(!$wallet)
        {
            $this->errorResponse = $this->errorResponse(
                422,
                'Validation error',
                [
                    'wallet_id' => "Invalid Wallet Id"
                ], 
            );

            return false;
        }
        

        if($wallet->is_locked->value)
        {
            $this->errorResponse = $this->errorResponse(
                message: 'Wallet locked',
            );

            return false;
        }
        

        if(!$wallet->is_outflow_allow)
        {
            $this->errorResponse = $this->errorResponse(
                message: 'Outward transaction not allowed',
            );

            return false;
        }


        if($wallet->balance < 0)
        {
            $this->errorResponse =  $this->errorResponse(
                message: 'Insufficient balance',
            );
            
            return false;
        }

       
        return true;
    }



    protected function CreateWalletObject($user, $validated)
    {


        // get Default wallet type
        $walletType = $this->walletTypeRepository->getByName($validated['wallet_name'])->firstOrFail();


        // Create Wallet Object
        $wallet = new Wallet;
        /* $wallet->is_limited = $walletType->id;
        $wallet->is_locked = $walletType->id; */
        $wallet->is_inflow_allow = 1;
        $wallet->is_outflow_allow = 1;
        $wallet->is_interest = 0;
        $wallet->country_id = $validated['country_id'];
        $wallet->wallet_type_id = $walletType->id;
        $wallet->created_by = $user->id;
        $wallet->user_id = $user->id;

        return $wallet;
    }

    
    
    protected function createWallets($user, $validated)
    { 

        $validated['user_id'] = $user->id;
        $validated['created_by'] = $user->id;

        $wallet = $this->walletRepository->create($validated);

        return $wallet; 
    }
}