<?php

use App\Http\Controllers\BalanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/update-balance/{user}/{amount}',
    [BalanceController::class, 'updateBalance']);