<?php 

namespace App\Interfaces\Repositories;


use App\Models\Profile;



interface IProfileRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByPhoneNo($phone_no);
    public function getByUserId($user_id);
    public function update(Profile $profile, $data);
    public function delete(Profile $profile);

}

