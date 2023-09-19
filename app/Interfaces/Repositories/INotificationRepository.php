<?php 

namespace App\Interfaces\Repositories;


use App\Models\Notification;



interface INotificationRepository{

    public function create($data);
    public function get(string $id);
    public function getAll();
    public function getByUserId(string $user_id);
    public function update(Notification $notification, $data);
    public function delete(Notification $notification);

}

