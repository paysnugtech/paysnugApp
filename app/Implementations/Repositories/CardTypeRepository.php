<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\ICardTypeRepository;
use App\Models\CardType;

class CardTypeRepository implements ICardTypeRepository{

    public function create($data)
    {
        return CardType::create($data);
    }
    
    public function fetch(string $id)
    {
        return CardType::with([])->where('id', $id);
    }
    
    public function fetchAll()
    {
        return CardType::with([])->get();
    }

    public function fetchByName($name)
    {
        return CardType::with([])->where('name', $name);
    }

    public function store(CardType $cardType){

        return $cardType->save();

    }
    
    public function update(CardType $cardType, $data)
    {
        return $cardType->update($data);
    }
    
    public function delete(CardType $cardType)
    {
        return $cardType->delete();
    }
    
}