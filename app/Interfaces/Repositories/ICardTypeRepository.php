<?php 

namespace App\Interfaces\Repositories;


use App\Models\CardType;



interface ICardTypeRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByName($name);
    public function store(CardType $cardType);
    public function update(CardType $cardType, $data);
    public function delete(CardType $cardType);

}

