<?php 

namespace App\Interfaces\Services;


interface IBankService{

    public function getAllBank();
    public function getBank(string $id);
    public function getBankByName(string $name);
    public function updateBank($request, $id);
    public function deleteBank(string $id);
}

