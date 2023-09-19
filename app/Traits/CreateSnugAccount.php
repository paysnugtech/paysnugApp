<?php 

namespace App\Traits;
use App\Interfaces\Repositories\IBankRepository;

trait CreateSnugAccount{
    
    protected $bankRepository;

    public function __construct(IBankRepository $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }
    
    protected function CreateSnugAccount($user, $data)
    {
        
        $data['number'] = (int)$user->phone_no;
        $data['user_id'] = $user->id;

        $account = $this->accountRepository->create($data);

        return $account;
    }
}