<?php 

namespace App\Traits;


use App\Interfaces\Repositories\IAccountRepository;
use App\Interfaces\Repositories\IBankRepository;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Repositories\IWalletTypeRepository;
use App\Models\WalletType;

trait CreateDefaultWallet{

    protected $accountRepository;
    protected $bankRepository;
    protected $walletRepository;
    protected $walletTypeRepository;

    public function __construct( 
        IAccountRepository $accountRepository, 
        IBankRepository $bankRepository, 
        IWalletRepository $walletRepository,
        IWalletTypeRepository $walletTypeRepository
    )
    {
        $this->accountRepository = $accountRepository;
        $this->bankRepository = $bankRepository;
        $this->walletRepository = $walletRepository;
        $this->walletTypeRepository = $walletTypeRepository;
    }

    
    
    protected function CreateDefaultWallet($user, $request)
    { 

        $validated = $request->validated();
        $validated['created_by'] = $user->id;

        // $walletType = $this->walletTypeRepository->getByName('Default')->firstOrFail();
        $walletType = WalletType::where('name', 'Default')->firstOrFail();

        $validated['wallet_type_id'] = $walletType->id;
        $validated['user_id'] = $user->id;

        
        $wallet = $this->walletRepository->create($validated);

        // $newWallet = $this->walletRepository->get($wallet->id)->firstOrFail();

        return $wallet; 
    }
}