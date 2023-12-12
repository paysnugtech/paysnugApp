<?php

namespace App\Http\Controllers\V1;

use App\Models\AirtimeBeneficiary;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAirtimeBeneficiaryRequest;
use App\Http\Requests\UpdateAirtimeBeneficiaryRequest;
use App\Interfaces\Services\IAirtimeBeneficiaryService;
use Illuminate\Http\Client\Request;

class AirtimeBeneficiariesController extends Controller
{


    protected $airtimeBeneficiaryService;

    public function __construct(IAirtimeBeneficiaryService $airtimeBeneficiaryService)
    {
        $this->airtimeBeneficiaryService = $airtimeBeneficiaryService;
    }



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
    public function store(StoreAirtimeBeneficiaryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AirtimeBeneficiary $airtimeBeneficiary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AirtimeBeneficiary $airtimeBeneficiary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAirtimeBeneficiaryRequest $request, AirtimeBeneficiary $airtimeBeneficiary)
    {
        //
    }



    /**
     * Display a listing of the resource.
     */
    public function userIndex(string $id)
    {

        $response = $this->airtimeBeneficiaryService->getBeneficiaryByUserId($id);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AirtimeBeneficiary $airtimeBeneficiary)
    {
        //
    }
}
