<?php 


namespace App\Implementations\Services;


use App\Http\Resources\WalletTypesResource;
use App\Interfaces\Repositories\IWalletTypeRepository;
use App\Interfaces\Services\IWalletTypeService;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;
use App\Traits\TokenResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class WalletTypeService implements IWalletTypeService
{
    use ErrorResponse, SuccessResponse, TokenResponse;


    protected $walletTypeRepository;

    public function __construct( 
        IWalletTypeRepository $walletTypeRepository,
    )
    {
        $this->walletTypeRepository = $walletTypeRepository;
    }
    
    public function getAllWalletType(){

        $walletType = $this->walletTypeRepository->getAll();

        if($walletType->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = WalletTypesResource::collection($walletType);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function getWalletType(string $id){
        
        $walletType = $this->walletTypeRepository->get($id)->firstOrFail();

        return $this->successResponse(
            new WalletTypesResource($walletType), 
            'Successful'
        );
    }
    
    public function getWalletTypeByName(string $name){

        $walletType = $this->walletTypeRepository->getByName($name)->firstOrFail();

        return $this->successResponse(
            new WalletTypesResource($walletType), 
            'Successful'
        );
    }

    
    public function createWalletType($request){

        $validated = $request->validated();

        $validated['created_by'] = 'user';

        DB::beginTransaction();

        // Save Wallet Type
        $walletType = $this->walletTypeRepository->create($validated);

        
        //DB::commit();


        return $this->successResponse(
            new WalletTypesResource($walletType), 
            'Updated Successful'
        );
    }
    
    public function updateWalletType($request, $id)
    {
        $validated = $request->validated();

        $walletType = $this->walletTypeRepository->get($id)->firstOrFail();
       
        DB::beginTransaction();

        $updateWallet = $this->walletTypeRepository->update($walletType, $validated);

        DB::commit();


        return $this->successResponse(
            new WalletTypesResource($updateWallet), 
            'Updated Successful'
        );
    }
    
    public function deleteWalletType(string $id){

        $walletType = $this->walletTypeRepository->get($id)->firstOrFail();

        $delete = $this->walletTypeRepository->delete($walletType);

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