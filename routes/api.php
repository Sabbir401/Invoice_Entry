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

Route::get('/order/{id?}',[OrderApiController::class, 'showOrder']);

Route::post('/add-order',[OrderApiController::class, 'addOrder']);

Route::post('/add-multiple-order',[OrderApiController::class, 'addMultipleOrder']);

Route::put('/update-order/{id}',[OrderApiController::class, 'updateOrder']);

Route::delete('/delete-order/{id}',[OrderApiController::class, 'deleteOrder']);

Route::delete('/delete-order',[OrderApiController::class, 'deleteOrderJson']);



