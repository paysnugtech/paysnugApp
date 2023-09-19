<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\IAddressRepository;
use App\Models\Address;

class AddressRepository implements IAddressRepository{


    public function create($data){

        return Address::create($data);
    }
    
    public function get(string $id){

        return Address::with(['country'])->where('id', $id);
    }
    
    public function getAll(){

        return Address::with(['country'])->all();
    }
    
    public function update(Address $address, $data){
        
        $address->update($data);

        return $address;
    }
    
    public function delete(Address $address){

        return $address->delete();
    }
    
}