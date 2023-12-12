<?php 

namespace App\Interfaces\Repositories;


use App\Models\Manager;



interface IManagerRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getRandomManager();
    public function store(Manager $manager);
    public function update(Manager $manager, $data);
    public function delete(Manager $manager);

}

