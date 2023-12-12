<?php 

namespace App\Interfaces\Repositories;


use App\Models\DataBeneficiary;



interface IDataBeneficiaryRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByCustomerId($customer_id);
    public function fetchByUserIdCustomerId($user_id, $customer_id);
    public function fetchByUserId($user_id);
    public function store(DataBeneficiary $dataBeneficiary);
    public function update(DataBeneficiary $dataBeneficiary, $data);
    public function delete(DataBeneficiary $dataBeneficiary);

}

