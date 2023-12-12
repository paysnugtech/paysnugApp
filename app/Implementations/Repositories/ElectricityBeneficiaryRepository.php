<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\IElectricityBeneficiaryRepository;
use App\Models\ElectricityBeneficiary;

class ElectricityBeneficiaryRepository implements IElectricityBeneficiaryRepository{


    public function create($data){
        
        $beneficiary = ElectricityBeneficiary::create($data);
        return $beneficiary;
    }
    
    public function fetch(string $id){

        return ElectricityBeneficiary::with(['user'])->where('id', $id);
    }
    
    public function fetchAll(){

        return ElectricityBeneficiary::with(['user'])->all();
    }


    public function fetchByCustomerId($customer_id)
    {
        $beneficiary = ElectricityBeneficiary::with(['user'])
            ->where('customer_id', $customer_id);

        return $beneficiary;
    }

    public function fetchByUserId($user_id)
    {
        return ElectricityBeneficiary::with(['user'])->where('user_id', $user_id);
    }


    public function fetchByUserIdCustomerId($user_id, $customer_id)
    {
        $beneficiary = ElectricityBeneficiary::with(['user'])
        ->where('user_id', $user_id)
        ->where('customer_id', $customer_id);

        return $beneficiary;
    }

    public function store(ElectricityBeneficiary $beneficiary)
    {

        $save = $beneficiary->save();

        return $save;
    }
    
    public function update(ElectricityBeneficiary $beneficiary, $data)
    {
        
        $update = $beneficiary->update($data);

        return $update;
    }
    
    public function delete(ElectricityBeneficiary $beneficiary)
    {

        $delete = $beneficiary->delete();

        return $delete;
    }
    
}