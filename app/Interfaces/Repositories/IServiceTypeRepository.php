<?php 

namespace App\Interfaces\Repositories;


use App\Models\ServiceType;



interface IServiceTypeRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByName(string $name);
    public function getChargesByName($name);
    public function getDiscountByName($name);
    public function getFeeByName(string $name);
    public function store(ServiceType $service);
    public function update(ServiceType $service, $data);
    public function delete(ServiceType $service);

}

