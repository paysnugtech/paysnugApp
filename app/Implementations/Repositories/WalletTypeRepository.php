<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IWalletTypeRepository;
use App\Models\WalletType;



class WalletTypeRepository implements IWalletTypeRepository{


    public function create($data){

        $obj = WalletType::create($data);

        return $obj;
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

    public function store(WalletType $obj)
    {

        $save = $obj->save();

        return $save;
    }
    
    public function update(WalletType $obj, $data){
        
        $obj->update($data);

        return $obj;
    }
    
    public function delete(WalletType $obj){

        return $obj->delete();
    }
    
}