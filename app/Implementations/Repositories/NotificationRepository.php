<?php 

namespace App\Implementations\Repositories;


use App\Interfaces\Repositories\INotificationRepository;
use App\Models\Notification;

class NotificationRepository implements INotificationRepository{


    public function create($data){

        return Notification::create($data);
    }
    
    public function get(string $id){

        return Notification::where('id', $id);
    }
    
    public function getAll(){

        return Notification::all();
    }

    public function getByUserId(string $user_id)
    {
        return Notification::where('user_id', $user_id);
    }
    
    public function update(Notification $notification, $data){
        
        $notification->update($data);

        return $notification;
    }
    
    public function delete(Notification $notification){

        return $notification->delete();
    }
    
}