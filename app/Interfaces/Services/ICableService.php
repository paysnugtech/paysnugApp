<?php 

namespace App\Interfaces\Services;



interface ICableService{

    public function removeBeneficiary($beneficiary);
    public function deleteCable(string $id);
    public function getBeneficiaryByUser($user);
    public function list();
    public function getProviderList();
    public function getPackageList($id);
    public function purchaseCable($request, $user);
    public function updateCable($request, $id);
    public function verifyCustomer($request);

}

