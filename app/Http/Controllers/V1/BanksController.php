<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\AccountEnquiryRequest;
use App\Interfaces\Services\IBankService;
use App\Models\Bank;
use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;
use App\Http\Requests\UserTransferRequest;
use Illuminate\Routing\Controller;

class BanksController extends Controller
{


    protected $bankService;


    public function __construct(IBankService $bankService)
    {
        $this->bankService = $bankService;
    }


    public function beneficiary()
    {
        $user = auth()->user();

        $response = $this->bankService->getBeneficiaryByUser($user);

        return $response;
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function enquiry(AccountEnquiryRequest $request)
    {
        
        $response = $this->bankService->getAccountDetails($request);

        return $response;
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->bankService->getAllBank();

        return $response;
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankRequest $request)
    {
        
        $response = $this->bankService->getBankByName($request);

        return $response;
    }


    /**
     * Display the specified resource.
     */
    public function show(string $bank_code)
    {
        $response = $this->bankService->getBank($bank_code);

        return $response;
        
    }


   
    public function transfer(UserTransferRequest $request)
    {

        $user = auth()->user();

        $response = $this->bankService->makeTransfer($user, $request);

        return $response;

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBankRequest $request, Bank $bank)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        //
    }
}
