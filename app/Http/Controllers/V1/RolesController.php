<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\RolesResource;
use App\Interfaces\Services\IRoleService;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RolesController extends Controller
{

    protected $roleService;


    public function __construct(IRoleService $roleService){

        $this->roleService = $roleService;
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->roleService->getAllRole();

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();

        $response = $this->roleService->storeRole($validated);

        return $response;
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $response = $this->roleService->getRole($id);

        return $response;

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {

        $response = $this->roleService->updateRole($request, $id);

        return $response;
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->roleService->deleteRole($id);

        return $response;
    }
}
