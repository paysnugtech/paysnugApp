<?php 

namespace App\Interfaces\Repositories;


use App\Models\User;



interface IUserRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByEmail($email);
    public function update(User $user, $data);
    public function delete(User $user);

}

