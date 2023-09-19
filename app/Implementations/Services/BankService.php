<?php 


namespace App\Implementations\Services;

use App\Http\Resources\BanksResource;
use App\Interfaces\Repositories\IBankRepository;
use App\Interfaces\Services\IBankService;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;
use App\Traits\TokenResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BankService implements IBankService
{
    use ErrorResponse, SuccessResponse, TokenResponse;


    protected $bankRepository;

    public function __construct(
        IBankRepository $bankRepository
    )
    {
        $this->bankRepository = $bankRepository;
    }
    
    public function getAllBank(){

        $wallet = $this->bankRepository->getAll();

        if($wallet->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = BanksResource::collection($wallet);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function getBank(string $id){
        
        $wallet = $this->bankRepository->get($id)->firstOrFail();

        if($wallet->user->id != Auth::id())
        {
            return $this->errorResponse(
                ['id' => 'Invalid wallet Id'], 
                'Validation error',
                422
            );
        }

        return $this->successResponse(
            new BanksResource($wallet), 
            'Successful'
        );
    }
    
    public function getBankByName(string $name){


        $bank = $this->bankRepository->getByName($name)->firstOrFail();

        return $this->successResponse(
            new BanksResource($bank), 
            'Successful'
        );
    }
    
    public function updateBank($request, $id)
    {
        $validated = $request->validated();

        $bank = $this->bankRepository->get($id)->firstOrFail();
       
        DB::beginTransaction();

        $updateBank = $this->bankRepository->update($bank, $validated);

        $bank->country->update($validated);

        DB::commit();


        return $this->successResponse(
            new BanksResource($updateBank), 
            'Updated Successful'
        );
    }
    
    public function deleteBank(string $id){
        
        $bank = $this->bankRepository->get($id)->firstOrFail();

        $delete = $this->bankRepository->delete($bank);

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