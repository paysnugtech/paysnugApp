<?php 


namespace App\Implementations\Services;

use App\Http\Resources\AccountsResource;
use App\Http\Resources\TransactionsResource;
use App\Interfaces\Repositories\ITransactionRepository;
use App\Interfaces\Services\ITransactionService;
use App\Traits\GenerateTransactionNo;
use App\Traits\ResponseTrait;


class TransactionService implements ITransactionService
{

    use GenerateTransactionNo,
        ResponseTrait;

    protected $transactionRepository;

    public function __construct(ITransactionRepository $transactionRepository)
    {

        $this->transactionRepository = $transactionRepository;
    }
    
    public function storeTransaction($request)
    {
        
        $validated = $request->validated();

        $transaction = $this->transactionRepository->create($validated);

        // print_r($validated);

        return $this->successResponse(
            new TransactionsResource($transaction),
            'Created Successful'
        );
    }
    
    public function getTransaction(string $id)
    {
        
        $transaction = $this->transactionRepository->get($id)->firstOrFail();

        return $this->successResponse(
            message: 'Successful',
            data: new TransactionsResource($transaction)
        );
    }
    
    public function getAllTransaction(){

        $transactions = $this->transactionRepository->getAll();

        if($transactions->count() < 1)
        {
            return $this->errorResponse(404, 'No record found');
        }

        $resource = TransactionsResource::collection($transactions);

        return $this->successResponse(message: 'Successful', data: $resource);

    }
    
    public function getTransactionByNumber()
    {
        $number = $this->generateTransactionNo();

        $transaction = $this->transactionRepository->getByNumber($number)->first();

        if($transaction)
        {
            return $this->errorResponse(
                message: 'Number not generated!' // 'Number already exist!'
            );
        }

        return $this->successResponse(
            message: 'Successful',
            data: [
                'number' => $number
            ], 
        );
    }
    
    public function getTransactionByUserId(string $user_id)
    {
        
        $transaction = $this->transactionRepository->getByUserId($user_id)->get();

        return $this->successResponse(
            message: 'Successful',
            data: TransactionsResource::collection($transaction), 
        );
    }


    public function getByUserIdServiceTypeAmount($user_id, $service_type, $amount)
    {
        $transaction = $this->transactionRepository->fetchByUserIdServiceTypeAmount($user_id, $service_type, $amount)->firstOrFail();

        return $this->successResponse(
            message: 'Successful',
            data: new TransactionsResource($transaction)
        );
    }

    
    public function getTransactionByStatus($status)
    {
        
        $transaction = $this->transactionRepository->get($status)->firstOrFail();

        return $this->successResponse(
            message: 'Successful',
            data: new TransactionsResource($transaction), 
        );
    }
    
    public function updateTransaction($request, $id)
    {
        $validated = $request->validated();

        $transaction = $this->transactionRepository->get($id)->firstOrFail();

        $update = $this->transactionRepository->update($transaction, $validated);

        return $this->successResponse(
            message: 'Updated Successful',
            data: new TransactionsResource($update)
        );
    }
    
    public function deleteTransaction(string $id){

        $user = $this->transactionRepository->get($id)->firstOrFail();

        $delete = $this->transactionRepository->delete($user);

        if(!$delete)
        {
            return $this->errorResponse(
                message: 'Something went wrong, User not Deleted|'
            );
        }

        return $this->successResponse(
            message: 'Deleted Successful'
        );
    }
    
}