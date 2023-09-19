<?php 


namespace App\Implementations\Services;

use App\Http\Resources\AddressesResource;
use App\Interfaces\Repositories\IAddressRepository;
use App\Interfaces\Services\IAddressService;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;


class AddressService implements IAddressService
{
    use ErrorResponse, SuccessResponse;

    protected $addressRepository;

    public function __construct(IAddressRepository $addressRepository)
    {

        $this->addressRepository = $addressRepository;
    }
    
    public function storeAddress($request)
    {
        
        $validated = $request->validated();

        $address = $this->addressRepository->create($validated);

        // print_r($validated);

        return $this->successResponse(
            new AddressesResource($address),
            'Created Successful'
        );
    }
    
    public function getAddress(string $id)
    {
        
        $user = $this->addressRepository->get($id)->firstOrFail();

        return $this->successResponse(
            new AddressesResource($user), 
            'Successful'
        );
    }
    
    public function getAllAddress(){

        $user = $this->addressRepository->getAll();

        if($user->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = AddressesResource::collection($user);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function updateAddress($request, $id)
    {
        $validated = $request->validated();

        $user = $this->addressRepository->get($id)->firstOrFail();

        $update = $this->addressRepository->update($user, $validated);

        return $this->successResponse(
            new AddressesResource($update), 
            'Updated Successful'
        );
    }
    
    public function deleteAddress(string $id){

        $user = $this->addressRepository->get($id)->firstOrFail();

        $delete = $this->addressRepository->delete($user);

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