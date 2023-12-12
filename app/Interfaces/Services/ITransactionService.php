<?php 

namespace App\Interfaces\Services;



interface ITransactionService{

    public function storeTransaction($data);
    public function getTransaction(string $id);
    public function getAllTransaction();
    public function getTransactionByNumber();
    public function getTransactionByUserId(string $user_id);
    public function getTransactionByStatus($status);
    public function updateTransaction($request, $id);
    public function deleteTransaction(string $id);

}

