<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\ICableBeneficiaryRepository;
use App\Models\CableBeneficiary;

class CableBeneficiaryRepository implements ICableBeneficiaryRepository{


    public function create($data){
        
        $beneficiary = CableBeneficiary::create($data);
        return $beneficiary;
    }
    
    public function fetch(string $id){

        return CableBeneficiary::with(['user'])->where('id', $id);
    }
    
    public function fetchAll(){

        return CableBeneficiary::with(['user'])->all();
    }


    public function fetchByCustomerId($customer_id)
    {
        $beneficiary = CableBeneficiary::with(['user'])
            ->where('customer_id', $customer_id);

        return $beneficiary;
    }

    public function fetchByUserId($user_id)
    {
        return CableBeneficiary::with(['user'])->where('user_id', $user_id);
    }


    public function fetchByUserIdCustomerId($user_id, $customer_id)
    {
        $beneficiary = CableBeneficiary::with(['user'])
        ->where('user_id', $user_id)
        ->where('customer_id', $customer_id);

        return $beneficiary;
    }

    public function store(CableBeneficiary $beneficiary)
    {

        $save = $beneficiary->save();

        return $save;
    }
    
    public function update(CableBeneficiary $beneficiary, $data)
    {
        
        $update = $beneficiary->update($data);

        return $update;
    }
    
    public function delete(CableBeneficiary $beneficiary)
    {

        $delete = $beneficiary->delete();

        return $delete;
    }
    
}