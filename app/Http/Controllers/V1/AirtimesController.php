<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAirtimeRequest;
use App\Interfaces\Services\IAirtimeService;
use App\Models\AirtimeBeneficiary;
use Illuminate\Http\Request;

class AirtimesController extends Controller
{
    
    protected $airtimeService;

    public function __construct(IAirtimeService $airtimeService)
    {
        $this->airtimeService = $airtimeService;
    }
    


    public function index()
    {
        /* $response = $this->airtimeService->list();

        return $response; */
    }


    
    public function providerList()
    {
        
        $response = $this->airtimeService->getProviderList();

        return $response;
    }
    


    public function store(StoreAirtimeRequest $request)
    {

        $user = $request->user();

        $response = $this->airtimeService->purchaseAirtime($request, $user);

        return $response;
    }
    

    public function deleteBeneficiary(AirtimeBeneficiary $beneficiary)
    {

        $user = auth()->user();

        $response = $this->airtimeService->removeBeneficiary($beneficiary, $user);

        return $response;

    }



    public function userBeneficiary()
    {
        $user = auth()->user();

        $response = $this->airtimeService->getBeneficiaryByUser($user);

        return $response;
    }
}
