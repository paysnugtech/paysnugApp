<?php 

namespace App\Interfaces\Repositories;


use App\Models\AirtimeBeneficiary;



interface IAirtimeBeneficiaryRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByPhoneNo($phone_no);
    public function fetchByUserIdCustomerId($user_id, $customer_id);
    public function fetchByUserId($user_id);
    public function store(AirtimeBeneficiary $airtimeBeneficiary);
    public function update(AirtimeBeneficiary $airtimeBeneficiary, $data);
    public function delete(AirtimeBeneficiary $airtimeBeneficiary);

}

