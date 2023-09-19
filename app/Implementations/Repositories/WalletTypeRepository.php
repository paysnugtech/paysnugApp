<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IWalletTypeRepository;
use App\Models\WalletType;



class WalletTypeRepository implements IWalletTypeRepository{


    public function create($data){

        $walletType = WalletType::create($data);

        return $walletType;
    }
    
    public function get(string $id){

        return WalletType::with(['wallets'])->where('id', $id);
    }
    
    public function getAll(){

        return WalletType::with(['wallets'])->get();
    }

    public function getByName($name)
    {
        // return WalletType::with(['wallets'])->where('name', $name);
        return WalletType::where('name', $name);
    }
    
    public function update(WalletType $walletType, $data){
        
        $walletType->update($data);

        return $walletType;
    }
    
    public function delete(WalletType $walletType){

        return $walletType->delete();
    }
    
}