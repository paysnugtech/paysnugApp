<?php 

namespace App\Interfaces\Repositories;


use App\Models\Electricity;



interface IElectricityRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByName($name);
    public function fetchCustomer($data);
    public function fetchPackages($data);
    public function fetchProvider(string $id);
    public function fetchProviders();
    
    public function store(Electricity $electricity);
    public function update(Electricity $electricity, $data);
    public function delete(Electricity $electricity);

}

