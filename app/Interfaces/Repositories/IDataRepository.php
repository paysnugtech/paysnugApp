<?php 

namespace App\Interfaces\Repositories;


use App\Models\Data;



interface IDataRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByName($name);
    public function fetchPackages($data);
    public function fetchProvider(string $id);
    public function fetchProviders();
    
    public function store(Data $data);
    public function update(Data $obj, $data);
    public function delete(Data $data);

}

