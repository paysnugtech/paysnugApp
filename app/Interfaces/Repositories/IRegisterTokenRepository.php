<?php 

namespace App\Interfaces\Repositories;


use App\Models\DeviceToken;
use App\Models\RegisterToken;






interface IRegisterTokenRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function store(RegisterToken $registerToken);
    public function update(RegisterToken $registerToken, $data);
    public function delete(RegisterToken $registerToken);

}

