<?php

use App\Http\Controllers\kapal_controller;
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


Route::post('/store-kapal-api', [kapal_controller::class, 'store_kapal_api']);
Route::get('/get-kapal-desc-api',[kapal_controller::class, 'show_kapal_detail_api']);