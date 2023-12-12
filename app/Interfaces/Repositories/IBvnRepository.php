<?php 

namespace App\Interfaces\Repositories;


use App\Models\Bvn;



interface IBvnRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByUserId($user_id);
    public function store(Bvn $bvn);
    public function update(Bvn $bvn, $data);
    public function delete(Bvn $bvn);

}

