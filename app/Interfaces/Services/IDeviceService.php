<?php 

namespace App\Interfaces\Services;


interface IDeviceService{

    public function getAllDevice();
    public function getDevice(string $id);
    public function deleteDevice(string $id);
    public function storeDevice($request);
    public function updateDevice($request, $id);
}

