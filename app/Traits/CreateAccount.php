<?php 

namespace App\Traits;
use App\Interfaces\Repositories\IAccountRepository;
use App\Interfaces\Repositories\IBankRepository;

trait CreateAccount{

    use ErrorResponse;
    protected $accountRepository;
    protected $bankRepository;

    public function __construct(IAccountRepository $accountRepository, IBankRepository $bankRepository)
    {
        $this->accountRepository = $accountRepository;
        $this->bankRepository = $bankRepository;
    }
    
    protected function CreateAccount($user, $wallet)
    {

        $data['created_by'] = $user->id;
        $data['user_id'] = $user->id;
        $data['wallet_id'] = $wallet->id;
        $data['number'] = (int)$user->profile->phone_no;

        
        $bank = $this->bankRepository->getByName('Paysnug')->firstOrFail();
        $data['bank_id'] = $bank->id;

        $accountExist = $this->accountRepository->getByAccountNoAndBankId($data['number'], $data['bank_id'])->first();

        if($accountExist)
        {
            return false;
        }

        $this->accountRepository->create($data);

        $account = $this->accountRepository->getByWalletId($wallet->id);

        return $account;
    }
}