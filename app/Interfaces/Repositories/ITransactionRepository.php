<?php 

namespace App\Interfaces\Repositories;


use App\Models\Transaction;



interface ITransactionRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByNumber($account_no);
    public function getByUserId($user_id);
    public function fetchByUserIdServiceTypeAmount($user_id, $service_type, $amount);
    public function getByStatus($status);
    public function store(Transaction $transaction);
    public function update(Transaction $transaction, $data);
    public function delete(Transaction $transaction);

}

