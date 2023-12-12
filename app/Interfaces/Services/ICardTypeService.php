<?php 

namespace App\Interfaces\Services;


interface ICardTypeService{

    public function getAllCardType();
    public function getCardType(string $id);
    public function storeCardType($request);
    public function updateCardType($request, $id);
    public function deleteCardType(string $id);
}

