<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Interfaces\Services\IWalletService;
use Illuminate\Support\Facades\Auth;

class WalletsController extends Controller
{

    protected $walletService;


    public function __construct(IWalletService $walletService)
    {
        $this->walletService = $walletService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $response = $this->walletService->getAllWallet();

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWalletRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $response = $this->walletService->getWallet($id);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, string $id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function user()
    {
        $id = Auth::id();

        print_r('test');

        /* $response = $this->walletService->getWalletByUserId($id);

        return $response; */
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
