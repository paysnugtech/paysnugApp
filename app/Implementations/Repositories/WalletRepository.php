<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IWalletRepository;
use App\Models\Wallet;

class WalletRepository implements IWalletRepository{


    public function create($data){
        // print_r($data);
        $wallet = Wallet::create($data);
        return $wallet;
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
    
    public function update(Wallet $wallet, $data){
        
        $wallet->update($data);

        return $wallet;
    }
    
    public function delete(Wallet $wallet){

        return $wallet->delete();
    }
    
}