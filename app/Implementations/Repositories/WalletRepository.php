<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IWalletRepository;
use App\Models\Wallet;

class WalletRepository implements IWalletRepository{


    public function create($data){
        // print_r($data);
        $obj = Wallet::create($data);

        return $obj;
    }
    
    public function get(string $id){

        return Wallet::with(['accounts', 'country', 'type', 'user'])->where('id', $id);
    }
    
    public function getAll(){

        return Wallet::with(['accounts', 'country', 'type', 'user'])->get();
    }

    public function getByUserId($user_id)
    {
        return Wallet::with(['accounts', 'country', 'type', 'user'])->where('user_id', $user_id);
    }

    public function store(Wallet $obj)
    {
        $save = $obj->save();

        return $save;
    }
    
    public function update(Wallet $obj, $data){
        
        $obj->update($data);

        return $obj;
    }
    
    public function delete(Wallet $obj){

        return $obj->delete();
    }
    
}