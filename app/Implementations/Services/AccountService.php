<?php 


namespace App\Implementations\Services;

use App\Http\Resources\AccountsResource;
use App\Interfaces\Repositories\IAccountRepository;
use App\Interfaces\Services\IAccountService;
use App\Traits\ErrorResponse;
use App\Traits\SuccessResponse;


class AccountService implements IAccountService
{
    use ErrorResponse, SuccessResponse;

    protected $accountRepository;

    public function __construct(IAccountRepository $accountRepository)
    {

        $this->accountRepository = $accountRepository;
    }
    
    public function storeAccount($request)
    {
        
        $validated = $request->validated();

        $address = $this->accountRepository->create($validated);

        // print_r($validated);

        return $this->successResponse(
            new AccountsResource($address),
            'Created Successful'
        );
    }
    
    public function getAccount(string $id)
    {
        
        $user = $this->accountRepository->get($id)->firstOrFail();

        return $this->successResponse(
            new AccountsResource($user), 
            'Successful'
        );
    }
    
    public function getAllAccount(){

        $user = $this->accountRepository->getAll();

        if($user->count() < 1)
        {
            return $this->ErrorResponse([], 'No record found', 404);
        }

        $resource = AccountsResource::collection($user);

        return $this->successResponse($resource, 'Successful');

    }
    
    public function getAccountByBankId(string $bank_id)
    {
        
        $user = $this->accountRepository->get($bank_id)->firstOrFail();

        return $this->successResponse(
            new AccountsResource($user), 
            'Successful'
        );
    }
    
    public function getAccountByUserId(string $user_id)
    {
        
        $user = $this->accountRepository->get($user_id)->firstOrFail();

        return $this->successResponse(
            new AccountsResource($user), 
            'Successful'
        );
    }
    
    public function getAccountByWalletId(string $wallet_id)
    {
        
        $user = $this->accountRepository->get($wallet_id)->firstOrFail();

        return $this->successResponse(
            new AccountsResource($user), 
            'Successful'
        );
    }
    
    public function updateAccount($request, $id)
    {
        $validated = $request->validated();

        $user = $this->accountRepository->get($id)->firstOrFail();

        $update = $this->accountRepository->update($user, $validated);

        return $this->successResponse(
            new AccountsResource($update), 
            'Updated Successful'
        );
    }
    
    public function deleteAccount(string $id){

        $user = $this->accountRepository->get($id)->firstOrFail();

        $delete = $this->accountRepository->delete($user);

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