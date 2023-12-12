<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\ICardRepository;
use App\Models\Card;

class CardRepository implements ICardRepository{

    public function create($data)
    {
        return Card::create($data);
    }
    
    public function fetch(string $id)
    {
        return Card::with([])->where('id', $id);
    }
    
    public function fetchAll()
    {
        return Card::with([])->get();
    }

    public function fetchByUserId($user_id)
    {
        return Card::with([])->where('$user_id', $user_id);
    }

    public function store(Card $card){

        return $card->save();

    }
    
    public function update(Card $card, $data)
    {
        return $card->update($data);
    }
    
    public function delete(Card $card)
    {
        return $card->delete();
    }
    
}