<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MessageController::class, 'index'])->name('main.page');
Route::post('/store', [MessageController::class, 'store'])->name('store');
