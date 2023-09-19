<?php

namespace App\Http\Controllers\V1;


use App\Interfaces\Services\IWalletTypeService;
use App\Models\WalletType;
use App\Http\Requests\StoreWalletTypeRequest;
use App\Http\Requests\UpdateWalletTypeRequest;
use Illuminate\Routing\Controller;

class WalletTypesController extends Controller
{


    protected $walletTypeService;


    public function __construct(IWalletTypeService $walletTypeService)
    {
        $this->walletTypeService = $walletTypeService;
    }




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $walletTypes = $this->walletTypeService->getAllWalletType();

        return $walletTypes;
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
    public function store(StoreWalletTypeRequest $request)
    {
        $walletType = $this->walletTypeService->createWalletType($request);

        return $walletType;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $walletType = $this->walletTypeService->getWalletType($id);

        return $walletType;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WalletType $walletType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletTypeRequest $request, string $id)
    {
        $update = $this->walletTypeService->updateWalletType($request, $id);

        return $update;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WalletType $walletType)
    {
        //
    }
}
