<?php 

namespace App\Interfaces\Services;



interface INotificationService{

    public function storeNotification($data);
    public function getNotification(string $id);
    public function getAllNotification();
    public function getByUserId(string $user_id);
    public function updateNotification($request, $id);
    public function deleteNotification(string $id);
}

