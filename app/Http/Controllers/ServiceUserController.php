<?php

namespace App\Http\Controllers;

use App\Models\service_user;
use App\Http\Requests\Storeservice_userRequest;
use App\Http\Requests\Updateservice_userRequest;

class ServiceUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storeservice_userRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(service_user $service_user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(service_user $service_user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updateservice_userRequest $request, service_user $service_user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(service_user $service_user)
    {
        //
    }
}
