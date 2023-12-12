<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCardTypeRequest;
use App\Http\Requests\UpdateCardTypeRequest;
use App\Interfaces\Services\ICardTypeService;
use App\Models\CardType;
use App\Traits\ResponseTrait;

class CardTypesController extends Controller
{
    use ResponseTrait;

    protected $cardTypeService;


    public function __construct( 
        ICardTypeService $cardTypeService
    )
    {

        $this->cardTypeService = $cardTypeService;
    }




    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = $this->cardTypeService->getAllCardType();

        return $response;
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
    public function store(StoreCardTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CardType $idCardType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CardType $idCardType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCardTypeRequest $request, CardType $idCardType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CardType $idCardType)
    {
        //
    }
}
