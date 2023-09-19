<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IAccountRepository;
use App\Models\Account;

class AccountRepository implements IAccountRepository{


    public function create($data){
        
        $account = Account::create($data);
        
        return $account;
    }
    

    public function get(string $id){

        return Account::with(['bank', 'user', 'wallet'])->where('id', $id);
    }
    

    public function getAll(){

        return Account::with(['bank', 'user', 'wallet'])->get();
    }

    public function getByAccountNo($account_no)
    {
        return Account::with(['bank', 'user', 'wallet'])->where('number', $account_no);
    }

    public function getByAccountNoAndBankId($account_no, $bank_id)
    {
        return Account::with(['bank', 'user', 'wallet'])
            ->where('number', $account_no)
            ->where('bank_id', $bank_id);
    }

    public function getByBankId($bank_id)
    {
        return Account::with(['bank', 'user', 'wallet'])->where('bank_id', $bank_id);
    }

    public function getByUserId($user_id)
    {
        return Account::with(['bank', 'user', 'wallet'])->where('user_id', $user_id);
    }

    public function getByWalletId($wallet_id)
    {
        return Account::with(['bank', 'user', 'wallet'])->where('wallet_id', $wallet_id);
    }
    
    public function update(Account $account, $data){
        
        $account->update($data);

        return $account;
    }
    
    public function delete(Account $account){

        return $account->delete();
    }
    
}