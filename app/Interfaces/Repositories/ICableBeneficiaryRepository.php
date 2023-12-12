<?php 

namespace App\Interfaces\Repositories;


use App\Models\CableBeneficiary;



interface ICableBeneficiaryRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByCustomerId($customer_id);
    public function fetchByUserIdCustomerId($user_id, $customer_id);
    public function fetchByUserId($user_id);
    public function store(CableBeneficiary $beneficiary);
    public function update(CableBeneficiary $beneficiary, $data);
    public function delete(CableBeneficiary $beneficiary);

}

