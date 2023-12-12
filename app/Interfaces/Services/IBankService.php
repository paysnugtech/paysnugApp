<?php 

namespace App\Interfaces\Services;


interface IBankService{

    public function getAllBank();
    public function getBank(string $id);
    public function getBeneficiaryByUser($user);
    public function getBankByName($request);
    public function getAccountDetails($request);
    public function makeTransfer($user, $request);
}

