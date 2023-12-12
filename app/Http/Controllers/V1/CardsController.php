<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\Services\ICardService;
use App\Models\Card;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;

class CardsController extends Controller
{
    

    protected $cardService;


    public function __construct(ICardService $cardService)
    {
        $this->cardService = $cardService;
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
    public function store(StoreCardRequest $request)
    {

        $user = $request->user();
        
        $response = $this->cardService->storeIdCard($request, $user);

        return $response;
    }


    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCardRequest $request, Card $card)
    {
        //
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        //
    }
}
