<?php 

namespace App\Interfaces\Services;


interface IAddressService{

    public function storeAddress($data);
    public function getAddress(string $id);
    public function getAllAddress();
    public function updateAddress($request, $id);
    public function deleteAddress(string $id);
}

