<?php 

namespace App\Interfaces\Services;


interface IBillService{

    public function list();
    public function getBill(string $id);
    public function payBill($request, $user);
    public function storeUtilityBill($request, $user);
    public function verifyBill($bill, $user);
    public function removeBill(string $id);
    public function updateBill($request);
}

