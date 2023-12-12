<?php 

namespace App\Interfaces\Repositories;


use App\Models\Airtime;



interface IAirtimeRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByName($name);
    public function getProvider(string $id);
    public function getProviders();
    
    public function store(Airtime $airtime);
    public function update(Airtime $airtime, $data);
    public function delete(Airtime $airtime);

}

