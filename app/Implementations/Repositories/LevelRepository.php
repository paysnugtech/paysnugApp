<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\ILevelRepository;
use App\Models\Level;

class LevelRepository implements ILevelRepository{


    public function create($data){
    
        return Level::create($data);
    }
    
    public function get(string $id){

        return Level::where('id', $id);
    }
    
    public function getAll(){

        return Level::all();
    }

    public function getByCode($code)
    {
        return Level::where('code', $code);
    }

    public function getByUserId($user_id)
    {
        return Level::where('user_id', $user_id);
    }

    public function store(Level $obj)
    {

        $save = $obj->save();

        return $save;
    }
    
    public function update(Level $obj, $data){
        
        $update = $obj->update($data);

        return $update;
    }
    
    public function delete(Level $obj){

        $delete = $obj->delete();

        return $delete;
    }
    
}