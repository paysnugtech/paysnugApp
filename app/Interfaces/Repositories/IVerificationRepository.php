<?php 

namespace App\Interfaces\Repositories;


use App\Models\Verification;



interface IVerificationRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByUserId($user_id);
    public function update(Verification $verification, $data);
    public function delete(Verification $verification);

}

