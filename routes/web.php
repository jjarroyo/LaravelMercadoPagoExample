<?php

use App\Http\Controllers\MercadoPagoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::get('pay/{id}',[MercadoPagoController::class,"payProduct"]);
Route::get('sale/complete',[MercadoPagoController::class,"completeTransaction"]);
Route::get('sale/error',[MercadoPagoController::class,"errorTransaction"]);
Route::get('sale/pending',[MercadoPagoController::class,"pendingTransaction"]);