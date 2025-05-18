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

Route::get('/subtractValue', function () {
    return view('subtractValueAccount');
})->name('subtractValue');
route::post('/newAccount', [ContaController::class, 'store'])->name('store');
route::post('/checkBalance', [ContaController::class, 'getBalance'])->name('getBalance');
route::post('/subtractValue', [ContaController::class, 'subtractValue'])->name('subtractValueAccount');

Route::get('/addValue', function () {
    return view('addValueAccount');
})->name('addValue');
route::post('/addValue', [ContaController::class, 'addValue'])->name('addValueAccount');

