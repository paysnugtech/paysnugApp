<?php 

namespace App\Interfaces\Services;



interface ICableBeneficiaryService{

    public function list();
    public function getBeneficiary($id);
    public function getBeneficiaryByCustomerId($customer_id);
    public function getBeneficiaryByUserId($user_id);
    public function getBeneficiaryByUserIdCustomerId($user_id, $customer_id);
    public function storeBeneficiary($request, $user);
    public function deleteBeneficiary(string $id);
    public function updateBeneficiary($request, $id);

}

