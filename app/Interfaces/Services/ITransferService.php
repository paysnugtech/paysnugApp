<?php 

namespace App\Interfaces\Services;


interface ITransferService{

    
    public function getUser(string $id);
    public function getAllUser();
    public function getWallet($user);
    public function makeTransfer($user, $request);
}

