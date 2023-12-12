<?php


use App\Http\Controllers\V1\AirtimesController;
use App\Http\Controllers\V1\BvnsController;
use App\Http\Controllers\V1\BanksController;
use App\Http\Controllers\V1\BillsController;
use App\Http\Controllers\V1\CablesController;
use App\Http\Controllers\V1\CardsController;
use App\Http\Controllers\V1\CardTypesController;
use App\Http\Controllers\V1\DatasController;
use App\Http\Controllers\V1\ProfilesController;
use App\Http\Controllers\V1\RegistersController;
use App\Http\Controllers\V1\DevicesController;
use App\Http\Controllers\V1\TokensController;
use App\Http\Controllers\V1\TransactionsController;
use App\Http\Controllers\V1\TransfersController;
use App\Http\Controllers\V1\VerificationsController;
use App\Http\Controllers\V1\WebhooksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CountriesController;
use App\Http\Controllers\V1\ElectrictiesController;
use App\Http\Controllers\V1\ManagersController;
use App\Http\Controllers\V1\PasswordsController;
use App\Http\Controllers\V1\RolesController;
use App\Http\Controllers\V1\UsersController;
use App\Http\Controllers\V1\WalletsController;
use App\Http\Controllers\V1\WalletTypesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post("register", [AuthController::class, 'register']);


Route::prefix('v1')->group(function () {
    
    Route::controller(AuthController::class)->group(function(){
        Route::post('login/token', 'token')->name('token');
        Route::post('login', 'login')->name('login');
    });

    
    Route::post('/devices/verify', [DevicesController::class, 'store']);

    Route::post('/passwords/forgot', [PasswordsController::class, 'forgot']);
    Route::post('/passwords/reset', [PasswordsController::class, 'reset']);
    // Route::post('/users', [UsersController::class, 'store']);
    /* Route::post('/registers/token/verify', [RegistersController::class, 'verifyToken']);
    Route::post('/registers/token', [RegistersController::class, 'token']); */

    Route::controller(RegistersController::class)->group(function(){
        Route::post('/registers/token', 'token');
        Route::post('/registers/token/verify', 'verifyToken');
    });
    Route::apiResource('registers', RegistersController::class)->only(['store']);

    Route::controller(TokensController::class)->group(function(){
        Route::post('/tokens/password/reset', 'storeResetPasswordToken');
        // Route::post('/tokens/generate', 'generateToken');
    });

    Route::controller(WebhooksController::class)->group(function(){
        Route::post('/webhooks/reversal', 'reversal')->name('reversal');
        Route::post('/webhooks/settlement', 'settlement')->name('settlement');
        Route::post('/webhooks/notification', 'notification')->name('notification');
    });
    
});


Route::middleware('auth:api')->prefix('v1')->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('payload', [AuthController::class, 'payload']);

    Route::controller(AirtimesController::class)->group(function(){
        
        Route::delete('/airtimes/beneficiary/{beneficiary}', 'deleteBeneficiary');
        Route::get('/airtimes/beneficiary', 'userBeneficiary');
        Route::get('/airtimes/provider', 'providerList');
        
    });
    Route::apiResource('airtimes', AirtimesController::class)->only(['store', 'index', 'show']);

    
    
    Route::controller(BanksController::class)->group(function(){
        Route::post('/banks/account/enquiry', 'enquiry');
        Route::get('/banks/beneficiary', 'beneficiary');
        Route::post('/banks/transfer', 'transfer');
    });
    Route::apiResource('banks', BanksController::class)->only(['store', 'index', 'show']);


    Route::controller(BillsController::class)->group(function(){
        Route::post('/bills/upload', 'upload');
        Route::post('/bills/pay', 'pay');
    });
    Route::apiResource('bills', BillsController::class)->only(['index']);

    Route::apiResource('bvns', BvnsController::class)->only(['store']);

    Route::controller(CablesController::class)->group(function(){
        
        Route::delete('/cables/beneficiaries/{beneficiary}', 'deleteBeneficiary');
        Route::get('/cables/beneficiaries', 'userBeneficiary');
        Route::post('/cables/customer/verify', 'verifyCustomer');
        Route::get('/cables/provider', 'providerIndex');
        Route::post('/cables/package', 'package');
    });
    Route::apiResource('cables', CablesController::class)->only(['store', 'index', 'show']);

    Route::post('cards/upload', [CardsController::class, 'store']);
    Route::apiResource('cards', CardsController::class)->only(['index']);

    Route::apiResource('card-types', CardTypesController::class)->only(['index']);

    Route::controller(DatasController::class)->group(function(){
        Route::get('/datas/beneficiaries', 'beneficiary');
        Route::delete('/datas/beneficiaries/{beneficiary}', 'deleteBeneficiary');
        Route::get('/datas/package', 'package');
        Route::get('/datas/providers', 'providerList');
        
    });
    Route::apiResource('datas', DatasController::class)->only(['store', 'index', 'show']);

    Route::controller(ElectrictiesController::class)->group(function(){
        Route::get('/electricities/beneficiaries', 'userBeneficiary');
        Route::delete('/electricities/beneficiaries/{beneficiary}', 'deleteBeneficiary');
        Route::post('/electricities/customer/verify', 'verify');
        Route::get('/electricities/provider', 'providerIndex');
        Route::post('/electricities/package', 'package');
    });
    Route::apiResource('electricities', ElectrictiesController::class)->only(['store', 'index', 'show']);

    /* Route::controller(ElectricityBeneficiariesController::class)->group(function(){
        
        Route::get('/electricities/beneficiaries', 'userIndex');
    }); */

    Route::apiResource('devices', DevicesController::class)->except(['store']);

    Route::apiResource('managers', ManagersController::class);

    Route::get('/countries/available', [CountriesController::class, 'available']);
    Route::apiResource('countries', CountriesController::class)->except(['destroy']);

   
    Route::controller(PasswordsController::class)->group(function(){
        Route::get('/passwords/confirm', 'confirm');
        Route::post('/passwords/change', 'change');
    });
    Route::apiResource('passwords', PasswordsController::class)->except(['destroy', 'index', 'show', 'store', 'update']);

    
    Route::post('/users/profile/picture', [ProfilesController::class, 'storeProfilePicture']);
    Route::apiResource('profiles', ProfilesController::class)->except(['destroy', 'index', 'show', 'store', 'update']);
    

    Route::apiResource('roles', RolesController::class);

    Route::controller(TokensController::class)->group(function(){
        Route::post('/tokens/password/change', 'storeChangePasswordToken');
        Route::post('/tokens/pin/reset', 'storeRestPinToken');
        Route::post('/tokens/verify/finger-print', 'verifyFingerPrintToken');
        Route::post('/tokens/verify/pin', 'verifyPinToken');
    });

    

    Route::get('/transactions/number', [TransactionsController::class, 'number']);
    Route::apiResource('transactions', TransactionsController::class)->only(['index', 'show']);


    Route::controller(UsersController::class)->group(function(){
        Route::post('/users/finger/print', 'storeFingerPrint');
        Route::post('/users/notification', 'notification');
        Route::post('/users/pin/change', 'changePin');
        Route::post('/users/pin/reset', 'resetPin');
        Route::post('/users/profile', 'profile');
        Route::post('/users/wallets', 'wallets');
    });
    Route::apiResource('users', UsersController::class)->only(['index', 'show', 'update', 'destroy']);

    Route::post('/users/verifications/bvn', [VerificationsController::class, 'bvn']);
    Route::post('/users/cards/upload', [VerificationsController::class, 'storeCard']);
    Route::apiResource('verifications', VerificationsController::class)->except(['destroy', 'index', 'store', 'update']);

    Route::apiResource('wallet/type', WalletTypesController::class)->except(['destroy', 'store', 'update']);
    
   
    Route::apiResource('wallets', WalletsController::class)->except([]);
    
});



/* Route::middleware('auth:api')->controller(AuthController::class)->group(function(){
    
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('payload', 'payload');
}); */
