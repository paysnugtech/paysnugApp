<?php 

namespace App\Interfaces\Services;


interface IVerificationService{

    public function getVerification(string $id);
    public function getAllVerification();
    public function storeVerification($data);
    public function updateVerification($request, $id);
    public function verifyBill($request, $id);
    public function verifyBvn($request, $id);
    public function verifyCard($request, $id);
    public function deleteVerification(string $id);
}

