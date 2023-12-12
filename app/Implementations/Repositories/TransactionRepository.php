<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\ITransactionRepository;
use App\Models\Transaction;

class TransactionRepository implements ITransactionRepository{


    public function create($data){
        
        $transaction = Transaction::create($data);
        
        return $transaction;
    }
    

    public function get(string $id){

        return Transaction::with(['user'])->where('id', $id);
    }
    

    public function getAll(){

        return Transaction::with(['user'])
        ->orderBy('order_by', 'DESC')->get();
    }

    public function getByNumber($number)
    {
        return Transaction::with(['user'])
        ->where('number', $number);
    }

    public function fetchByUserIdServiceTypeAmount($user_id, $service_type, $amount)
    {
        return Transaction::with(['user'])
        ->where('user_id', $user_id)
        ->where('service_type', $service_type)
        ->where('amount', $amount)
        ->orderBy('order_by', 'DESC');
    }

    public function getByUserId($user_id)
    {
        return Transaction::with(['user'])
        ->where('user_id', $user_id)
        ->orderBy('order_by', 'DESC');
    }

    public function getByStatus($status)
    {
        return Transaction::with(['user'])
        ->where('status', $status)
        ->orderBy('order_by', 'DESC');
    }
    
    public function store(Transaction $transaction){
        
        $obj = $transaction->save();

        return $obj;
    }
    
    public function update(Transaction $transaction, $data){
        
        $transaction->update($data);

        return $transaction;
    }
    
    public function delete(Transaction $transaction){

        return $transaction->delete();
    }
    
}