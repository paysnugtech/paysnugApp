<?php 

namespace App\Implementations\Repositories;

use App\Enums\BankStatusEnum;
use App\Enums\VirtualAccountEnum;
use App\Interfaces\Repositories\IBankTransferRepository;
use App\Models\BankTransfer;

class BankTransferRepository implements IBankTransferRepository{


    public function create($data){

        $obj = BankTransfer::create($data);
        return $obj;
    }
    
    public function get(string $id){

        return BankTransfer::with(['accounts'])->where('id', $id);
    }
    
    public function getAll(){

        return BankTransfer::with(['accounts'])->get();
    }

    public function getByUserId($user_id)
    {
        
        $objs = BankTransfer::with(['user'])->where('user_id', $user_id)->get();

        return $objs;
    }

    public function store(BankTransfer $bank){

        $obj = $bank->save();

        return $obj;
    }



    
    public function update(BankTransfer $obj, $data){
        
        $obj->update($data);

        return $obj;
    }
    
    public function delete(BankTransfer $obj){

        return $obj->delete();
    }
    
}