<?php 

namespace App\Interfaces\Repositories;


use App\Models\DeviceToken;






interface IDeviceTokenRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByEmail($email);
    public function save(DeviceToken $deviceToken);
    public function update(DeviceToken $token, $data);
    public function delete(DeviceToken $token);

}

