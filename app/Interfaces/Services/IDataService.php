<?php 

namespace App\Interfaces\Services;

use App\Models\DataBeneficiary;

interface IDataService{

    public function deleteData(string $id);
    public function deleteBeneficiary(DataBeneficiary $beneficiary);
    public function getBeneficiaryByUser($user);
    public function list();
    public function packageList($id);
    public function providerList();
    public function purchaseData($request, $user);
    public function updateData($request, $id);

}

