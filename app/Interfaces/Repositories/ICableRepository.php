<?php 

namespace App\Interfaces\Repositories;


use App\Models\Cable;



interface ICableRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByCustomerId($customer_id);
    public function fetchByUserId($user_id);
    public function fetchCustomer($data);
    public function fetchPackages($data);
    public function fetchProvider(string $id);
    public function fetchProviders();
    
    public function store(Cable $electricity);
    public function update(Cable $electricity, $data);
    public function delete(Cable $electricity);

}

