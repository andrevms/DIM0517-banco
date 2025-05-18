<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContaController;

Route::get('/', function () {
    return view('index');
})->name('formLogin');

Route::get('/newAccount', function () {
    return view('createAccount');
})->name('createAccount');
Route::get('/checkBalance', function () {
    return view('checkBalance');
})->name('checkBalance');
route::post('/newAccount', [ContaController::class, 'store'])->name('store');
route::post('/checkBalance', [ContaController::class, 'getBalance'])->name('getBalance');

