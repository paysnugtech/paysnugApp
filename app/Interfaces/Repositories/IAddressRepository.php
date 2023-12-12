<?php 

namespace App\Interfaces\Repositories;


use App\Models\Address;



interface IAddressRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function store(Address $address);
    public function update(Address $address, $data);
    public function delete(Address $address);

}

