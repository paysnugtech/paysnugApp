<?php

namespace App\Http\Controllers\V1;

use App\Models\DataBeneficiary;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDataBeneficiaryRequest;
use App\Http\Requests\UpdateDataBeneficiaryRequest;

class DataBeneficiariesController extends Controller
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
    public function store(StoreDataBeneficiaryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DataBeneficiary $dataBeneficiary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataBeneficiary $dataBeneficiary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDataBeneficiaryRequest $request, DataBeneficiary $dataBeneficiary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataBeneficiary $dataBeneficiary)
    {
        //
    }
}
