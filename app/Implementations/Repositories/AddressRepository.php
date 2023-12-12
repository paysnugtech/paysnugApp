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

    public function store(Address $address){

        $obj = $address->save();

        return $obj;
    }
    
    public function update(Address $obj, $data){
        
        $obj->update($data);

        return $obj;
    }
    
    public function delete(Address $obj){

        return $obj->delete();
    }
    
}