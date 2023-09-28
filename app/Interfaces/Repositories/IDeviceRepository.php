<?php 

namespace App\Interfaces\Repositories;


use App\Models\Device;



interface IDeviceRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByUserId($user_id);
    public function save(Device $device);
    public function update(Device $device, $data);
    public function delete(Device $device);

}

