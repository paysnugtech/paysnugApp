<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\ILogRepository;
use App\Models\Log;

class LogRepository implements ILogRepository{


    public function create($data){

        $obj = Log::create($data);

        return $obj;
    }
    
    public function delete(Log $obj){

        return $obj->delete();
    }
    
    public function get(string $id){

        return Log::with([
            'user'
        ])->where('id', $id);
    }
    
    public function getAll(){

        return Log::with([
            'user'
        ])->get();
    }

    public function getByUserId($user_id)
    {
        return Log::with([
            'user'
        ])->where('user_id', $user_id);
    }


    
    public function store(Log $log)
    {

        $obj = $log->save();
        
        return $obj;
    }

    
    public function update(Log $obj, $data){
        
        $obj->update($data);

        return $obj;
    }
    
}