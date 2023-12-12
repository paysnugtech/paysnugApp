<?php 

namespace App\Interfaces\Repositories;


use App\Models\ElectricityBeneficiary;



interface IElectricityBeneficiaryRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByCustomerId($customer_id);
    public function fetchByUserId($user_id);
    public function fetchByUserIdCustomerId($user_id, $customer_id);
    public function store(ElectricityBeneficiary $beneficiary);
    public function update(ElectricityBeneficiary $beneficiary, $data);
    public function delete(ElectricityBeneficiary $beneficiary);

}

