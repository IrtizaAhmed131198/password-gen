<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;

Route::get('/', [PasswordController::class, 'index'])->name('home');
Route::post('/generate', [PasswordController::class, 'generate'])->name('password.generate');
