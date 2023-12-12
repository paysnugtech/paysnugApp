<?php 

namespace App\Interfaces\Repositories;


use App\Models\Country;



interface ICountryRepository{

    public function create($data);
    public function get(string $id);
    public function getByName(string $name);
    public function getByStatus($status);
    public function getAll();
    public function store(Country $country);
    public function update(Country $country, $data);
    public function delete(Country $country);

}

