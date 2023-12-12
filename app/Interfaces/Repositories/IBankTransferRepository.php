<?php 

namespace App\Interfaces\Repositories;


use App\Models\BankTransfer;



interface IBankTransferRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByUserId(string $user_id);
    public function store(BankTransfer $bankTransfer);
    public function update(BankTransfer $bankTransfer, $data);
    public function delete(BankTransfer $bankTransfer);

}

