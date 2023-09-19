<?php 

namespace App\Interfaces\Repositories;


use App\Models\Bvn;
use App\Models\Profile;



interface IBvnRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByUserId($user_id);
    public function update(Bvn $bvn, $data);
    public function delete(Bvn $bvn);

}

