<?php 

namespace App\Interfaces\Repositories;


use App\Models\Level;



interface ILevelRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByCode($code);
    public function getByUserId($user_id);
    public function store(Level $level);
    public function update(Level $level, $data);
    public function delete(Level $level);

}

