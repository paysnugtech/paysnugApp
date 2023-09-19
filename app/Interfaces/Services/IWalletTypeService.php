<?php 

namespace App\Interfaces\Services;


interface IWalletTypeService{

    public function createWalletType($request);
    public function getAllWalletType();
    public function getWalletType(string $id);
    public function getWalletTypeByName(string $name);
    public function updateWalletType($request, $id);
    public function deleteWalletType(string $id);
}

