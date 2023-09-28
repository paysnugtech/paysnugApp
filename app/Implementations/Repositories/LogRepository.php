<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\ILogRepository;
use App\Models\Log;

class LogRepository implements ILogRepository{


    public function create($data){

        $log = Log::create($data);

        return $log;
    }
    
    public function delete(Log $log){

        return $log->delete();
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


    
    public function save(Log $log)
    {

        $obj = $log->save();
        
        return $obj;
    }

    
    public function update(Log $log, $data){
        
        $log->update($data);

        return $log;
    }
    
}