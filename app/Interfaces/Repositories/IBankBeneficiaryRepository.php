<?php 

namespace App\Interfaces\Repositories;


use App\Models\BankBeneficiary;



interface IBankBeneficiaryRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByAccountNoAndUserId($account_no, $user_id);
    public function getByUserId($user_id);
    public function store(BankBeneficiary $bankBeneficiary);
    public function update(BankBeneficiary $bankBeneficiary, $data);
    public function delete(BankBeneficiary $bankBeneficiary);

}

