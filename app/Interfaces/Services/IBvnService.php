<?php 

namespace App\Interfaces\Services;


interface IBvnService{

    public function list();
    public function getBvn(string $id);
    public function storeBvn($request, $user);
    public function verifyBvn($bvn, $user);
    public function removeBvn(string $id);
    public function updateBvn($request);
}

