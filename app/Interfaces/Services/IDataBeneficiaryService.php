<?php 

namespace App\Interfaces\Services;



interface IDataBeneficiaryService{

    public function list();
    public function getBeneficiary($id);
    public function getBeneficiaryByPhoneNo($phone_no);
    public function getBeneficiaryByUserId($user_id);
    public function getBeneficiaryByPhoneNoUserId($phone_no, $user_id,);
    public function storeBeneficiary($request, $user);
    public function deleteBeneficiary(string $id);
    public function updateBeneficiary($request, $id);

}

