<?php 

namespace App\Interfaces\Services;


interface ICountryService{

    public function storeCountry($data);
    public function getAllCountry();
    public function getCountry(string $id);
    public function getCountryAvailable();
    public function getCountryByName(string $name);
    public function updateCountry($request, $id);
    public function deleteCountry(string $id);
}

