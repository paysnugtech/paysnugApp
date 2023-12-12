<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\ITransactionService;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{

    protected $transactionService;


    public function __construct(ITransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    
    public function number()
    {
       
        $response = $this->transactionService->getTransactionByNumber();

        return $response;
    }

    
    public function index(Request $request)
    {
        $user = $request->user();
        $response = $this->transactionService->getTransactionByUserId($user->id);

        return $response;
    }

    
    public function show(string $id)
    {
       
        $response = $this->transactionService->getTransaction($id);

        return $response;
    }
}
