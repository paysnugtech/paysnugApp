<?php 


namespace App\Implementations\Services;

use App\Http\Resources\RolesResource;
use App\Interfaces\Repositories\IRoleRepository;
use App\Interfaces\Services\IRoleService;
use App\Models\Role;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;


class RoleService implements IRoleService
{
    use ErrorResponse, SuccessResponse;

    protected $roleRepository;

    public function __construct(IRoleRepository $roleRepository){

        $this->roleRepository = $roleRepository;
    }
    
    public function storeRole($data){
        
        $role = $this->roleRepository->create($data);

        return $this->successResponse(
            new RolesResource($role), 
            'Created Successful'
        );
    }
    
    public function getRole(string $id){
        
        $role = $this->roleRepository->get($id);

        return $this->successResponse(
            new RolesResource($role), 
            'Successful'
        );
    }
    
    public function getAllRole(){

        $role = $this->roleRepository->getAll();

        if($role->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = RolesResource::collection($role);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function updateRole($request, $id)
    {
        $validated = $request->validated();

        $role = $this->roleRepository->get($id);

        $update = $this->roleRepository->update($role, $validated);

        return $this->successResponse(
            new RolesResource($update), 
            'Updated Successful'
        );
    }
    
    public function deleteRole(string $id){
        
        $role = $this->roleRepository->get($id);

        if(!$role)
        {
            return $this->errorResponse(
                [], 
                'Role not found|'
            );
        }


        $delete = $this->roleRepository->delete($role);

        if(!$delete)
        {
            return $this->errorResponse(
                [], 
                'Something went wrong, Role not Deleted|'
            );
        }

        return $this->successResponse(
            [], 
            'Deleted Successful'
        );
    }
    
}