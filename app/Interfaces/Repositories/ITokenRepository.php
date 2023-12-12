<?php 

namespace App\Interfaces\Repositories;


use App\Models\DeviceToken;
use App\Models\Token;






interface ITokenRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByEmail($email);
    public function fetchByEmailNumber($email, $number);
    public function fetchByEmailNumberType($email, $number, $type);
    public function fetchByEmailType($email, $type);
    public function fetchByType($type);
    public function store(Token $token);
    public function update(Token $token, $data);
    public function delete(Token $token);

}

