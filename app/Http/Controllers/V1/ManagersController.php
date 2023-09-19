<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ManagersResource;
use App\Interfaces\Services\IManagerService;
use App\Models\V1\Manager;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;

class ManagersController extends Controller
{

    use SuccessResponse, ErrorResponse;
    protected $managerService;

    public function __construct(IManagerService $managerService){
        
        $this->managerService = $managerService;
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $response = $this->managerService->getAllManager();
        
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreManagerRequest $request)
    {
        $validated = $request->validated();

        $response = $this->managerService->storeManager($validated);

        return $response;

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $response = $this->managerService->getManager($id);
        
        return $response;

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateManagerRequest $request, string $id)
    {
        $response = $this->managerService->updateManager($request, $id);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $response = $this->managerService->deleteManager($id);

        return $response;
    }
}
