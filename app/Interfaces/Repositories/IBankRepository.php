<?php 

namespace App\Interfaces\Repositories;


use App\Models\Bank;



interface IBankRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByName($name);
    public function getVirtualAccountBanks();
    public function update(Bank $bank, $data);
    public function delete(Bank $bank);

}

