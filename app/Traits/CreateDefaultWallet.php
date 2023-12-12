<?php 

namespace App\Traits;



use App\Interfaces\Repositories\IWalletRepository;

trait CreateDefaultWallet{

    protected $walletRepository;

    
    public function __construct( 
        IWalletRepository $walletRepository,
    ){
        $this->walletRepository = $walletRepository;
    }
    

    protected function CreateDefaultWallet($user, $validated)
    {

        $validated['created_by'] = $user->id;
        $validated['user_id'] = $user->id;

        $wallet = $this->walletRepository->create($validated);
        
        return $wallet;
    }
}