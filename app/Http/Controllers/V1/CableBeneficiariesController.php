<?php

namespace App\Http\Controllers\V1;

use App\Models\CableBeneficiary;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCableBeneficiaryRequest;
use App\Http\Requests\UpdateCableBeneficiaryRequest;

class CableBeneficiariesController extends Controller
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
    public function store(StoreCableBeneficiaryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CableBeneficiary $cableBeneficiary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CableBeneficiary $cableBeneficiary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCableBeneficiaryRequest $request, CableBeneficiary $cableBeneficiary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CableBeneficiary $cableBeneficiary)
    {
        //
    }
}
