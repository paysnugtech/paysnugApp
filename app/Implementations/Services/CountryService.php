<?php 


namespace App\Implementations\Services;

use App\Http\Resources\CountriesResource;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Services\ICountryService;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;


class CountryService implements ICountryService
{
    use ErrorResponse, SuccessResponse;

    protected $countryRepository;

    public function __construct(ICountryRepository $countryRepository)
    {

        $this->countryRepository = $countryRepository;
    }
    
    public function storeCountry($request)
    {
        
        $validated = $request->validated();

        $this->countryRepository->create($validated);

        $country = $this->countryRepository->getByName($validated['name'])->firstOrFail();

        // print_r($validated);

        return $this->successResponse(
            new CountriesResource($country),
            'Created Successful'
        );
    }
    
    public function getCountry(string $id)
    {
        
        $user = $this->countryRepository->get($id)->firstOrFail();

        return $this->successResponse(
            new CountriesResource($user), 
            'Successful'
        );
    }
    
    public function getAllCountry(){

        $user = $this->countryRepository->getAll();

        if($user->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = CountriesResource::collection($user);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function getCountryAvailable()
    {
        
        $users = $this->countryRepository->getByStatus(1)->get();

        if($users->count() < 1)
        {
            return $this->errorResponse(
                [], 
                'No country found!'
            );
        }

        return $this->successResponse(
            CountriesResource::collection($users), 
            'Successful'
        );
    }
    
    public function getCountryByName(string $name)
    {
        
        $user = $this->countryRepository->getByName($name)->firstOrFail();

        return $this->successResponse(
            new CountriesResource($user), 
            'Successful'
        );
    }
    
    public function updateCountry($request, $id)
    {
        $validated = $request->validated();

        $user = $this->countryRepository->get($id)->firstOrFail();

        $update = $this->countryRepository->update($user, $validated);

        return $this->successResponse(
            new CountriesResource($update), 
            'Updated Successful'
        );
    }
    
    public function deleteCountry(string $id){

        $user = $this->countryRepository->get($id)->firstOrFail();

        $delete = $this->countryRepository->delete($user);

        if(!$delete)
        {
            return $this->errorResponse(
                [], 
                'Something went wrong, User not Deleted|'
            );
        }

        return $this->successResponse(
            [], 
            'Deleted Successful'
        );
    }
    
}