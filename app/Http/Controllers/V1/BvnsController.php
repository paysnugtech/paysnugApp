<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\IBvnService;
use App\Models\Bvn;
use App\Http\Requests\StoreBvnRequest;
use App\Http\Requests\UpdateBvnRequest;

class BvnsController extends Controller
{

    protected $bvnService;



    public function __construct(IBvnService $bvnService)
    {
        $this->bvnService = $bvnService;
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
    public function store(StoreBvnRequest $request)
    {
        
        $user = $request->user();
        
        $response = $this->bvnService->storeBvn($request, $user);

        return $response;
    }

    /**
     * Display the specified resource.
     */
    public function show(Bvn $bvn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bvn $bvn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBvnRequest $request, Bvn $bvn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bvn $bvn)
    {
        //
    }
}
