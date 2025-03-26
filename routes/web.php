<?php

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

Route::get('/{code}', [LinkController::class, 'redirect'])->where('code', '[a-zA-Z0-9]{6}');
Route::view('/agendamento', 'agendamento');

Route::view('/', 'index');
