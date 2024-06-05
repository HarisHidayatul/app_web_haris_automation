<?php

use App\Http\Controllers\kapal_controller;
use Illuminate\Support\Facades\Route;
use Spatie\FlareClient\View;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     // return view('welcome');
//     return view('search_kapal.index');
// });

Route::get('/search-kapal', [kapal_controller::class, 'search_kapal'])->name('search.kapal');
Route::get('/search-kapal-form', [kapal_controller::class, 'search_kapal_form'])->name('search.kapal.form');
Route::get('/create-kapal', function(){
    return view('create_kapal.index');
});
Route::get('/create-kapal', [kapal_controller::class, 'create_kapal'])->name('create.kapal');
Route::post('/store-kapal', [kapal_controller::class, 'store_kapal'])->name('store.kapal');


Route::get('/show-kapal',[kapal_controller::class, 'show_kapal']);

Route::get('/show-kapal/{id}', [kapal_controller::class,'show_kapal_detail']);

Route::get('/search-pekerjaan', [kapal_controller::class, 'search_pekerjaan']);
