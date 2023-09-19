<?php


use App\Http\Controllers\V1\VerificationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CountriesController;
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
        Route::post('login', 'login')->name('login');
    });

    Route::post('/passwords/forgot', [PasswordsController::class, 'forgot']);
    Route::post('/passwords/reset', [PasswordsController::class, 'reset']);
    Route::post('/users', [UsersController::class, 'store']);
    
});


Route::middleware('auth:api')->prefix('v1')->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('payload', [AuthController::class, 'payload']);

    Route::apiResource('managers', ManagersController::class);

    Route::get('/countries/available', [CountriesController::class, 'available']);
    Route::apiResource('countries', CountriesController::class)->only(['index', 'update', 'store']);

    Route::get('/passwords/confirm', [PasswordsController::class, 'confirm']);
    Route::apiResource('passwords', PasswordsController::class)->only(['update']);
    Route::apiResource('roles', RolesController::class);
    Route::post('/users/finger-print/{user}', [UsersController::class, 'fingerPrint']);
    Route::post('/users/notification/{user}', [UsersController::class, 'notification']);
    Route::post('/users/pin/{user}', [UsersController::class, 'pin']);
    Route::patch('/users/profile/{id}', [UsersController::class, 'profile']);
    Route::apiResource('users', UsersController::class)->except(['create', 'edit', 'store']);

    Route::post('/users/verifications/bill/{user}', [VerificationsController::class, 'bill']);
    Route::post('/users/verifications/bvn/{user}', [VerificationsController::class, 'bvn']);
    Route::post('/users/verifications/card/{user}', [VerificationsController::class, 'card']);
    Route::apiResource('verifications', VerificationsController::class)->except(['create', 'edit', 'store']);

    Route::apiResource('wallet/type', WalletTypesController::class)->except(['create', 'destroy', 'edit', 'store', 'update']);
    
    Route::get('/wallets/user/{id}', [WalletsController::class, 'user']);
    Route::apiResource('wallets', WalletsController::class)->only(['index', 'show', 'update', 'destroy']);
    
});



/* Route::middleware('auth:api')->controller(AuthController::class)->group(function(){
    
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('payload', 'payload');
}); */
