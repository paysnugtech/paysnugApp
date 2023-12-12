<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IBillRepository;
use App\Models\Bill;

class BillRepository implements IBillRepository{


    public function create($data){

        $bill = Bill::create($data);
        return $bill;
    }
    
    public function fetch(string $id){

        return Bill::with(['user', 'verification'])->where('id', $id);
    }
    
    public function fetchAll(){

        return Bill::with(['user', 'verification'])->get();
    }

    public function fetchByUserId($user_id)
    {
        
        $bills = Bill::with(['user', 'verification'])->where('user_id', $user_id)->get();

        return $bills;
    }

    public function store(Bill $bill){

        return $bill->save();

    }



    
    public function update(Bill $bill, $data){
        
        $bill->update($data);

        return $bill;
    }
    
    public function delete(Bill $bill){

        return $bill->delete();
    }
    
}