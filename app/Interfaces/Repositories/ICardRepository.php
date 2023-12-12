<?php 

namespace App\Interfaces\Repositories;


use App\Models\Card;



interface ICardRepository{

    public function create($data);
    public function fetch(string $id);
    public function fetchAll();
    public function fetchByUserId($user_id);
    public function store(Card $card);
    public function update(Card $card, $data);
    public function delete(Card $card);

}

