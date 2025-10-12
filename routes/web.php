<?php

use App\Http\Controllers\RedeSocialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('redes-sociais', RedeSocialController::class);