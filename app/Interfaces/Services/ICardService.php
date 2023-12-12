<?php 

namespace App\Interfaces\Services;


interface ICardService{

    public function list();
    public function getCard(string $id);
    public function storeIdCard($request, $user);
    public function verifyCard($card, $user);
    public function removeCard(string $id);
    public function updateCard($request);
}

