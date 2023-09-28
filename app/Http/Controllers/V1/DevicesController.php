<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\IDeviceService;
use App\Models\Device;
use App\Http\Requests\StoreDeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;

class DevicesController extends Controller
{

    protected $deviceService;

    public function __construct(IDeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeviceRequest $request)
    {
        $response = $this->deviceService->storeDevice($request);

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeviceRequest $request, Device $device)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        //
    }
}
