<?php

use App\Http\Controllers\API\SalesController;
use App\Http\Controllers\API\SellersController;
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

Route::post('sellers',[SellersController::class,'createSellers']);

Route::get('sellers',[SellersController::class,'returnSellers']);

Route::get('sellers/{id}/sales',[SalesController::class,'getSalesSeller']);

Route::post('sales',[SalesController::class,'createSale']);

