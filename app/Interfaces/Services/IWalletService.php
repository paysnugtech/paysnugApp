<?php 

namespace App\Interfaces\Services;


interface IWalletService{

    public function getAllWallet();
    public function getWallet(string $id);
    public function getWalletByUserId(string $user_id);
    public function updateWallet($request, $id);
    public function deleteWallet(string $id);
}

