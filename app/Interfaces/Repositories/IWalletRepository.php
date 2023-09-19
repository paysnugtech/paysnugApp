<?php 

namespace App\Interfaces\Repositories;


use App\Models\Wallet;



interface IWalletRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByUserId($user_id);
    public function update(Wallet $wallet, $data);
    public function delete(Wallet $wallet);

}

