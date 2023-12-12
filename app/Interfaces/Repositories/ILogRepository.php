<?php 

namespace App\Interfaces\Repositories;


use App\Models\Log;



interface ILogRepository{

    public function create($data);
    public function delete(Log $log);
    public function get(string $id);
    public function getAll();
    public function getByUserId($user_id);
    public function store(Log $log);
    public function update(Log $log, $data);

}

