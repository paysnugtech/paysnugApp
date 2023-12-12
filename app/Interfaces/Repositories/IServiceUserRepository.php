<?php 

namespace App\Interfaces\Repositories;


use App\Models\ServiceUser;



interface IServiceUserRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByServiceId(string $service_id);
    public function getByUserId(string $user_id);
    public function store(ServiceUser $service);
    public function update(ServiceUser $service, $data);
    public function delete(ServiceUser $service);

}

