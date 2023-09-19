<?php 

namespace App\Implementations\Repositories;



use App\Interfaces\Repositories\IBvnRepository;
use App\Models\Bvn;

class BvnRepository implements IBvnRepository{

    public function create($data)
    {
        $bvn = Bvn::create($data);
        return $bvn;
    }
    
    public function get(string $id)
    {
        return Bvn::with([
            'user', 
            'verification'
        ])->where('id', $id);
    }
    
    public function getAll()
    {
        return Bvn::with([
            'user', 
            'verification'
        ])->get();
    }

    public function getByUserId($user_id)
    {
        return Bvn::with([
            'user', 
            'verification'
        ])->where('user_id', $user_id);
    }
    
    public function update(Bvn $bvn, $data)
    {
        $bvn->update($data);

        return $bvn;
    }
    
    public function delete(Bvn $bvn)
    {
        return $bvn->delete();
    }
    
}