<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Interfaces\Services\ICountryService;
use Illuminate\Http\Request;

class CountriesController extends Controller
{

    protected $countryService;

    public function __construct(ICountryService $countryService)
    {
        $this->countryService = $countryService;
    }


    public function available()
    {
        $response = $this->countryService->getCountryAvailable();

        return $response;
    }


    public function index()
    {
        $response = $this->countryService->getAllCountry();

        return $response;
    }


    public function store(StoreCountryRequest $request)
    {
        
        $response = $this->countryService->storeCountry($request);

        return $response;
    }


    public function update(UpdateCountryRequest $request, string $id)
    {
        
        $response = $this->countryService->updateCountry($request, $id);

        return $response;
    }


    


}
