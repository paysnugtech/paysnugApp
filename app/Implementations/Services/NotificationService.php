<?php 


namespace App\Implementations\Services;

use App\Http\Resources\NotificationsResource;
use App\Interfaces\Repositories\INotificationRepository;
use App\Interfaces\Services\INotificationService;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;


class NotificationService implements INotificationService
{
    use ErrorResponse, SuccessResponse;

    protected $notificationRepository;

    public function __construct(INotificationRepository $notificationRepository){

        $this->notificationRepository = $notificationRepository;
    }
    
    public function storeNotification($data){
        
        $notification = $this->notificationRepository->create($data);

        return $this->successResponse(
            new NotificationsResource($notification), 
            'Created Successful'
        );
    }
    
    
    public function getAllNotification(){

        $notifications = $this->notificationRepository->getAll();

        if($notifications->count() < 1)
        {
            return $this->errorResponse([], 'No record found', 404);
        }

        $resource = NotificationsResource::collection($notifications);

        return $this->successResponse($resource, 'Successful');

        // return $notifications;
    }


    public function getByUserId(string $user_id)
    {
        $notification = $this->notificationRepository->getByUserId($user_id)->firstOrFail();

        return $this->successResponse(
            new NotificationsResource($notification), 
            'Successful'
        );
    }
    
    public function getNotification(string $id){
        
        $notification = $this->notificationRepository->get($id)->firstOrFail();

        return $this->successResponse(
            new NotificationsResource($notification), 
            'Successful'
        );
    }

    
    public function updateNotification($request, $id)
    {
        $validated = $request->validated();

        $notification = $this->notificationRepository->get($id);

        $update = $this->notificationRepository->update($notification, $validated);

        return $this->successResponse(
            new NotificationsResource($update), 
            'Updated Successful'
        );

    }
    
    public function deleteNotification(string $id){
        
        $notification = $this->notificationRepository->get($id);

        if(!$notification)
        {
            return $this->errorResponse(
                [], 
                'Manager not found|'
            );
        }

        $delete = $this->notificationRepository->delete($notification);

        if(!$delete)
        {
            return $this->errorResponse(
                [], 
                'Something went wrong, Manager not Deleted|'
            );
        }

        return $this->successResponse(
            [], 
            'Deleted Successful'
        );
    }
    
}