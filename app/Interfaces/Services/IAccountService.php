<?php 

namespace App\Interfaces\Services;



interface IAccountService{

    public function storeAccount($data);
    public function getAccount(string $id);
    public function getAllAccount();
    public function getAccountByBankId(string $bank_id);
    public function getAccountByUserId(string $user_id);
    public function getAccountByWalletId(string $wallet_id);
    public function updateAccount($request, $id);
    public function deleteAccount(string $id);

}

