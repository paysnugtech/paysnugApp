<?php 

namespace App\Traits;
use App\Interfaces\Repositories\IAccountRepository;
use App\Interfaces\Repositories\IBankRepository;
use App\Interfaces\Repositories\IWalletRepository;

trait CreateWallet{


    protected $accountRepository;
    protected $bankRepository;
    protected $walletRepository;


    public function __construct(
        IAccountRepository $accountRepository, 
        IBankRepository $bankRepository, 
        IWalletRepository $walletRepository
        )
    {
        $this->accountRepository = $accountRepository;
        $this->bankRepository = $bankRepository;
    }

    
    
    protected function CreateWallet($user, $request)
    { 

        $validated = $request->validated();
        $validated['created_by'] = $user->id;

        $wallet = $user->wallets()->create($validated);


        // $bank = $this->bankRepository->getByName("Paysnug")->firstOrFail();

        $banks = $this->bankRepository->getVirtualAccountBanks();
 
        $validated['wallet_id'] = $wallet->id;

        foreach ($banks as $bank) {

            
            $validated['bank_id'] = $bank->id;

            if($bank->bank_code == "0000")
            {
                $this->CreateSnugAccount($user, $validated);
            }
        }
        
        

        // $wallet->accounts()->create($data);

        // print_r($banks);

        /* $account = $this->accountRepository->create($data);*/

        $newWallet = $this->walletRepository->get($wallet->id)->firstOrFail();

        return $newWallet; 
    }
}