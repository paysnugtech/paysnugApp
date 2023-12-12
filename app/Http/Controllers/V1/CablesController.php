<?php

namespace App\Http\Controllers\V1;

use App\Models\Cable;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetCablePackageRequest;
use App\Http\Requests\StoreCableRequest;
use App\Http\Requests\UpdateCableRequest;
use App\Http\Requests\VerifyCableCustomerRequest;
use App\Interfaces\Services\ICableService;
use App\Models\CableBeneficiary;
use Illuminate\Http\Request;

class CablesController extends Controller
{


    protected $cableService;


    /**
     * Display a listing of the resource.
     */
    public function __construct(ICableService $cableService)
    {
        $this->cableService = $cableService;
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
     * Display a listing of the resource.
     */
    public function deleteBeneficiary(CableBeneficiary $beneficiary)
    {


        $response = $this->cableService->removeBeneficiary($beneficiary);

        return $response;
        
    }



    public function package(GetCablePackageRequest $request)
    {

        $response = $this->cableService->getPackageList($request);

        return $response;
    }



    public function providerIndex()
    {
        $response = $this->cableService->getProviderList();

        return $response;
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCableRequest $request)
    {
        $user = auth()->user();
        
        $response = $this->cableService->purchaseCable($request, $user);

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(Cable $cable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cable $cable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCableRequest $request, Cable $cable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cable $cable)
    {
        //
    }



    /**
     * Display a listing of the resource.
     */
    public function userBeneficiary(Request $request)
    {

        $user = $request->user();

        $response = $this->cableService->getBeneficiaryByUser($user);

        return $response;
        
    }



    public function verifyCustomer(VerifyCableCustomerRequest $request)
    {
        $response = $this->cableService->verifyCustomer($request);

        return $response;
    }
}
