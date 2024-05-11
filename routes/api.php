<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderApiController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function(){
    Route::post('login','login');
    Route::post('register','register');
});

Route::get('/entry/{id?}',[OrderApiController::class, 'showOrder']);

Route::post('/add-entry',[OrderApiController::class, 'addOrder']);

Route::post('/add-multiple-entry',[OrderApiController::class, 'addMultipleOrder']);

Route::put('/update-entry/{id}',[OrderApiController::class, 'updateOrder']);

Route::delete('/delete-entry/{id}',[OrderApiController::class, 'deleteOrder']);

Route::delete('/delete-entry',[OrderApiController::class, 'deleteOrderJson']);



