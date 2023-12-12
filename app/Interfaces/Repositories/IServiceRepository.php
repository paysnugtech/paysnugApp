<?php 

namespace App\Interfaces\Repositories;


use App\Models\Service;



interface IServiceRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByName($name);
    public function fetchByUserId($user_id);
    public function fetchChargesByName($name);
    public function fetchDiscountByName($name);
    public function fetchFeeByName($name);
    public function store(Service $service);
    public function update(Service $service, $data);
    public function delete(Service $service);

}

