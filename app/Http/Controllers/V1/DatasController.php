<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetDataPackageRequest;
use App\Http\Requests\StoreDataRequest;
use App\Interfaces\Services\IDataService;
use App\Models\DataBeneficiary;
use Illuminate\Http\Request;

class DatasController extends Controller
{

    protected $dataService;

    public function __construct(IDataService $dataService)
    {
        $this->dataService = $dataService;
    }
    


    public function beneficiary(Request $request)
    {

        $user = $request->user();

        $response = $this->dataService->getBeneficiaryByUser($user);

        return $response;
        
    }
    


    public function deleteBeneficiary(DataBeneficiary $beneficiary)
    {
        // $user = $request->user();

        $response = $this->dataService->deleteBeneficiary($beneficiary);

        return $response;
        
    }



    public function index()
    {
        $response = $this->dataService->list();

        return $response;
    }
    


    public function store(StoreDataRequest $request)
    {

        $user = $request->user();

        $response = $this->dataService->purchaseData($request, $user);

        return $response;
    }
    


    public function show(StoreDataRequest $request)
    {

        $user = $request->user();

        $response = $this->dataService->purchaseData($request, $user);

        return $response;
    }


    public function package(GetDataPackageRequest $request)
    {
        
        $response = $this->dataService->packageList($request);

        return $response;
    }


    public function providerList()
    {
        
        $response = $this->dataService->providerList();

        return $response;
    }

}
