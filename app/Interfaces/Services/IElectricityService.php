<?php 

namespace App\Interfaces\Services;



interface IElectricityService{

    public function deleteBeneficiary($user, $beneficiary);
    public function getBeneficiaryByUser($user);
    public function list();
    public function providerList();
    public function packageList($id);
    public function purchaseElectricity($request, $user);
    public function updateElectricity($request, $id);
    public function verifyCustomer($request);
    public function deleteElectricity(string $id);

}

