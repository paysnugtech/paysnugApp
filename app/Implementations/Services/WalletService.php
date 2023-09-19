<?php 


namespace App\Implementations\Services;

use App\Http\Resources\WalletsResource;
use App\Interfaces\Repositories\ICountryRepository;
use App\Interfaces\Repositories\IUserRepository;
use App\Interfaces\Repositories\IWalletRepository;
use App\Interfaces\Services\IWalletService;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;
use App\Traits\TokenResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class WalletService implements IWalletService
{
    use ErrorResponse, SuccessResponse, TokenResponse;


    protected $countryRepository;
    protected $walletRepository;
    protected $userRepository;

    public function __construct(
        ICountryRepository $countryRepository, 
        IWalletRepository $walletRepository,
        IUserRepository $userRepository
    )
    {

        $this->countryRepository = $countryRepository;
        $this->walletRepository = $walletRepository;
        $this->userRepository = $userRepository;
    }
    
    public function getAllWallet(){

        $wallet = $this->walletRepository->getAll();

        if($wallet->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = WalletsResource::collection($wallet);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function getWallet(string $id){
        
        $wallet = $this->walletRepository->get($id)->firstOrFail();

        if($wallet->user->id != Auth::id())
        {
            return $this->errorResponse(
                ['id' => 'Invalid wallet Id'], 
                'Validation error',
                422
            );
        }

        return $this->successResponse(
            new WalletsResource($wallet), 
            'Successful'
        );
    }
    
    public function getWalletByUserId(string $user_id){
        
        if($user_id != Auth::id())
        {
            return $this->errorResponse(
                ['id' => 'Invalid user Id'], 
                'Validation error',
                422
            );
        }

        $wallets = $this->walletRepository->getByUserId($user_id)->get();

        return $this->successResponse(
            WalletsResource::collection($wallets), 
            'Successful'
        );
    }
    
    public function updateWallet($request, $id)
    {
        $validated = $request->validated();

        $wallet = $this->walletRepository->get($id)->firstOrFail();
       
        DB::beginTransaction();

        $updateWallet = $this->walletRepository->update($wallet, $validated);

        $wallet->country->update($validated);

        DB::commit();


        return $this->successResponse(
            new WalletsResource($updateWallet), 
            'Updated Successful'
        );
    }
    
    public function deleteWallet(string $id){

        $wallet = $this->walletRepository->get($id)->firstOrFail();

        $delete = $this->walletRepository->delete($wallet);

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