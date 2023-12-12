<?php 

namespace App\Interfaces\Repositories;


use App\Models\WalletType;



interface IWalletTypeRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByName($name);
    public function store(WalletType $wallet);
    public function update(WalletType $wallet, $data);
    public function delete(WalletType $wallet);

}

