<?php 

namespace App\Interfaces\Services;



interface IAirtimeService{

    public function getBeneficiaryByUser($user);
    public function getProviderList();
    public function list();
    public function purchaseAirtime($request, $user);
    public function removeBeneficiary($beneficiary, $user);
    public function updateAirtime($request, $id);
    public function deleteAirtime(string $id);

}

