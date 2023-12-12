<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserTransferRequest;
use App\Interfaces\Services\ITransferService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransfersController extends Controller
{

    protected $transferService;

    public function __construct(ITransferService $transferService)
    {
        $this->transferService = $transferService;
    }


    //
    public function store(UserTransferRequest $request)
    {

        $user = Auth::user();

        $response = $this->transferService->makeTransfer($user, $request);

        return $response;

    }

}
