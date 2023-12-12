<?php

namespace App\Http\Controllers\V1;

use App\Models\Electricity;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetPackageRequest;
use App\Http\Requests\StoreElectricityRequest;
use App\Http\Requests\UpdateElectricityRequest;
use App\Http\Requests\VerifyCustomerRequest;
use App\Interfaces\Services\IElectricityService;
use App\Models\ElectricityBeneficiary;
use Illuminate\Http\Request;

class ElectrictiesController extends Controller
{


    protected $electricityService;

    public function __construct(IElectricityService $electricityService)
    {
        $this->electricityService = $electricityService;
    }




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function package(GetPackageRequest $request)
    {
        
        $response = $this->electricityService->packageList($request);

        return $response;
    }




    /**
     * Display a listing of the resource.
     */
    public function providerIndex()
    {
        $response = $this->electricityService->providerList();

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }



    /**
     * Display a listing of the resource.
     */
    public function deleteBeneficiary(ElectricityBeneficiary $beneficiary)
    {

        $user = auth()->user();

        $response = $this->electricityService->deleteBeneficiary($user, $beneficiary);

        return $response;
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreElectricityRequest $request)
    {
        $user = $request->user();

        $response = $this->electricityService->purchaseElectricity($request, $user);

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(Electricity $electricity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Electricity $electricity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateElectricityRequest $request, Electricity $electricity)
    {
        //
    }



    /**
     * Display a listing of the resource.
     */
    public function userBeneficiary(Request $request)
    {

        $user = $request->user();

        $response = $this->electricityService->getBeneficiaryByUser($user);

        return $response;
        
    }


    public function verify(VerifyCustomerRequest $request)
    {
        
        $response = $this->electricityService->verifyCustomer($request);

        return $response;
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Electricity $electricity)
    {
        //
    }
}
