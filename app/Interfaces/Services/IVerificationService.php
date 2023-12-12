<?php 

namespace App\Interfaces\Services;


interface IVerificationService{

    public function getAllVerification();
    public function getVerification(string $id);
    public function storeVerification($request);
    public function updateVerification($request, $id);
    public function verifyBill($bill, $user);
    public function verifyBvn($request);
    public function verifyCard($request);
    public function deleteVerification(string $id);
}

