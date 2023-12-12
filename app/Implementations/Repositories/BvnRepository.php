<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\IBvnRepository;
use App\Models\Bvn;

class BvnRepository implements IBvnRepository{

    public function create($data)
    {
        $obj = Bvn::create($data);
        return $obj;
    }
    
    public function fetch(string $id)
    {
        return Bvn::with([
            'user', 
            'verification'
        ])->where('id', $id);
    }
    
    public function fetchAll()
    {
        return Bvn::with([
            'user', 
            'verification'
        ])->get();
    }

    public function fetchByUserId($user_id)
    {
        return Bvn::with([
            'user', 
            'verification'
        ])->where('user_id', $user_id);
    }

    public function store(Bvn $bvn){

        $obj = $bvn->save();

        return $obj;
    }
    
    public function update(Bvn $obj, $data)
    {
        $obj->update($data);

        return $obj;
    }
    
    public function delete(Bvn $obj)
    {
        return $obj->delete();
    }
    
}