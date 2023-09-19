<?php 

namespace App\Interfaces\Repositories;


use App\Models\Account;



interface IAccountRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByAccountNo($account_no);
    public function getByAccountNoAndBankId($account_no, $bank_id);
    public function getByBankId($bank_id);
    public function getByUserId($user_id);
    public function getByWalletId($wallet_id);
    public function update(Account $account, $data);
    public function delete(Account $account);

}

