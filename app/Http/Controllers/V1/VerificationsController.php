<?php

namespace App\Http\Controllers\V1;

use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\StoreBvnRequest;
use App\Http\Requests\StoreCardRequest;
use App\Interfaces\Services\IVerificationService;
use App\Models\Verification;
use App\Http\Requests\StoreVerificationRequest;
use App\Http\Requests\UpdateVerificationRequest;
use Illuminate\Routing\Controller;

class VerificationsController extends Controller
{

    protected $verificationService;


    public function __construct(IVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    
    /**
     * Verify user bvn.
     */
    public function bill(StoreBillRequest $request, string $id)
    {
        $response = $this->verificationService->verifyBill($request, $id);

        return $response;
    }

    
    /**
     * Verify user bvn.
     */
    public function bvn(StoreBvnRequest $request, string $id)
    {
        $response = $this->verificationService->verifyBvn($request, $id);

        return $response;
    }

    
    /**
     * Verify user bvn.
     */
    public function card(StoreCardRequest $request, string $id)
    {
        $response = $this->verificationService->verifyCard($request, $id);

        return $response;
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
    public function store(StoreVerificationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Verification $verification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Verification $verification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVerificationRequest $request, Verification $verification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Verification $verification)
    {
        //
    }
}
