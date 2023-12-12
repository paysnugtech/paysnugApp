<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\IBillService;
use App\Models\Bill;
use App\Http\Requests\PayBillRequest;
use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Http\Requests\UploadBillRequest;

class BillsController extends Controller
{

    protected $billService;



    public function __construct(IBillService $billService)
    {
        $this->billService = $billService;
    }





    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillRequest $request)
    {
        
        $user = $request->user();
        
        $response = $this->billService->storeUtilityBill($request, $user);

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill)
    {
        //
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function pay(PayBillRequest $request)
    {
        
        $user = $request->user();
        
        $response = $this->billService->payBill($request, $user);

        return $response;
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBillRequest $request, Bill $bill)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     */
    public function upload(UploadBillRequest $request)
    {
        
        $user = $request->user();
        
        $response = $this->billService->storeUtilityBill($request, $user);

        return $response;
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        //
    }
}
