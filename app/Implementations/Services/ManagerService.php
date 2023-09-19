<?php 


namespace App\Implementations\Services;

use App\Http\Resources\ManagersResource;
use App\Interfaces\Repositories\IManagerRepository;
use App\Interfaces\Services\IManagerService;
use App\Models\Manager;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;


class ManagerService implements IManagerService
{
    use ErrorResponse, SuccessResponse;

    protected $managerRepository;

    public function __construct(IManagerRepository $managerRepository){

        $this->managerRepository = $managerRepository;
    }
    
    public function storeManager($data){
        
        $manager = $this->managerRepository->create($data);

        return $this->successResponse(
            new ManagersResource($manager), 
            'Created Successful'
        );
    }
    
    public function getManager(string $id){
        
        $manager = $this->managerRepository->get($id);

        return $this->successResponse(
            new ManagersResource($manager), 
            'Successful'
        );
    }
    
    public function getAllManager(){

        $managers = $this->managerRepository->getAll();

        if($managers->count() < 1)
        {
            return $this->errorResponse([], 'No record found', 404);
        }

        $resource = ManagersResource::collection($managers);

        return $this->successResponse($resource, 'Successful');

        // return $managers;
    }
    
    public function updateManager($request, $id)
    {
        $validated = $request->validated();

        $manager = $this->managerRepository->get($id);

        $update = $this->managerRepository->update($manager, $validated);

        return $this->successResponse(
            new ManagersResource($update), 
            'Updated Successful'
        );

    }
    
    public function deleteManager(string $id){
        
        $manager = $this->managerRepository->get($id);

        if(!$manager)
        {
            return $this->errorResponse(
                [], 
                'Manager not found|'
            );
        }

        $delete = $this->managerRepository->delete($manager);

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