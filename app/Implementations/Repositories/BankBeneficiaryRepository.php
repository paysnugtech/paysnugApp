<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\IBankBeneficiaryRepository;
use App\Models\BankBeneficiary;

class BankBeneficiaryRepository implements IBankBeneficiaryRepository{


    public function create($data){
        
        $bankBeneficiary = BankBeneficiary::create($data);
        return $bankBeneficiary;
    }
    
    public function get(string $id){

        return BankBeneficiary::with(['user'])->where('id', $id);
    }
    
    public function getAll(){

        return BankBeneficiary::with(['user'])->all();
    }

    public function getByAccountNoAndUserId($account_no, $user_id)
    {
        $beneficiary = BankBeneficiary::with(['user'])
            ->where('account_no', $account_no)
            ->where('user_id', $user_id);

        return $beneficiary;
    }

    public function getByUserId($user_id)
    {
        return BankBeneficiary::with(['user'])->where('user_id', $user_id);
    }

    public function store(BankBeneficiary $bankBeneficiary)
    {

        $save = $bankBeneficiary->save();

        return $save;
    }
    
    public function update(BankBeneficiary $bankBeneficiary, $data)
    {
        
        $update = $bankBeneficiary->update($data);

        return $update;
    }
    
    public function delete(BankBeneficiary $bankBeneficiary)
    {

        $delete = $bankBeneficiary->delete();

        return $delete;
    }
    
}