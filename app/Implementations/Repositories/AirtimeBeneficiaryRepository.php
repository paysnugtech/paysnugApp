<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\IAirtimeBeneficiaryRepository;
use App\Models\AirtimeBeneficiary;

class AirtimeBeneficiaryRepository implements IAirtimeBeneficiaryRepository{


    public function create($data){
        
        $airtimeBeneficiary = AirtimeBeneficiary::create($data);
        return $airtimeBeneficiary;
    }
    
    public function fetch(string $id){

        return AirtimeBeneficiary::with(['user'])->where('id', $id);
    }
    
    public function fetchAll(){

        return AirtimeBeneficiary::with(['user'])->all();
    }


    public function fetchByPhoneNo($phone_no)
    {
        $beneficiary = AirtimeBeneficiary::with(['user'])
            ->where('phone_no', $phone_no);

        return $beneficiary;
    }


    public function fetchByUserIdCustomerId($user_id, $customer_id)
    {
        $beneficiary = AirtimeBeneficiary::with(['user'])
            ->where('customer_id', $customer_id)
            ->where('user_id', $user_id);

        return $beneficiary;
    }

    public function fetchByUserId($user_id)
    {
        return AirtimeBeneficiary::with(['user'])->where('user_id', $user_id);
    }

    public function store(AirtimeBeneficiary $airtimeBeneficiary)
    {

        $save = $airtimeBeneficiary->save();

        return $save;
    }
    
    public function update(AirtimeBeneficiary $airtimeBeneficiary, $data)
    {
        
        $update = $airtimeBeneficiary->update($data);

        return $update;
    }
    
    public function delete(AirtimeBeneficiary $airtimeBeneficiary)
    {

        $delete = $airtimeBeneficiary->delete();

        return $delete;
    }
    
}