<?php 

namespace App\Interfaces\Repositories;



interface IPasswordResetRepository{

    public function create($data);
    public function getByEmail($email);
    public function getByEmailAndToken($data);
    public function getAll();
    public function save($data);
    public function update($data);
    public function delete($obj);

}

