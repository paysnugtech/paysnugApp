<?php 

namespace App\Interfaces\Repositories;


use App\Models\Service;



interface IServiceRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByUserId($user_id);
    public function update(Service $service, $data);
    public function delete(Service $service);

}

