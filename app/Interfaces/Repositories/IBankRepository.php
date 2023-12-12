<?php 

namespace App\Interfaces\Repositories;


use App\Models\Bank;



interface IBankRepository{

    public function accountEnquiry($data);
    public function create($data);
    public function fetchAllBank();
    public function get(string $id);
    public function getAll();
    public function getByName($name);
    public function getVirtualAccountBanks();
    public function store(Bank $bank);
    public function update(Bank $bank, $data);
    public function delete(Bank $bank);

}

