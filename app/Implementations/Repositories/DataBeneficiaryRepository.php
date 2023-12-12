<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\IDataBeneficiaryRepository;
use App\Models\DataBeneficiary;

class DataBeneficiaryRepository implements IDataBeneficiaryRepository{


    public function create($data){
        
        $beneficiary = DataBeneficiary::create($data);
        return $beneficiary;
    }
    
    public function fetch(string $id){

        return DataBeneficiary::with(['user'])->where('id', $id);
    }
    
    public function fetchAll(){

        return DataBeneficiary::with(['user'])->all();
    }


    public function fetchByCustomerId($customer_id)
    {
        $beneficiary = DataBeneficiary::with(['user'])
            ->where('customer_id', $customer_id);

        return $beneficiary;
    }


    public function fetchByUserIdCustomerId($user_id, $customer_id)
    {
        $beneficiary = DataBeneficiary::with(['user'])
            ->where('user_id', $user_id)
            ->where('customer_id', $customer_id);

        return $beneficiary;
    }

    public function fetchByUserId($user_id)
    {
        return DataBeneficiary::with(['user'])->where('user_id', $user_id);
    }

    public function store(DataBeneficiary $beneficiary)
    {

        $save = $beneficiary->save();

        return $save;
    }
    
    public function update(DataBeneficiary $beneficiary, $data)
    {
        
        $update = $beneficiary->update($data);

        return $update;
    }
    
    public function delete(DataBeneficiary $beneficiary)
    {

        $delete = $beneficiary->delete();

        return $delete;
    }
    
}