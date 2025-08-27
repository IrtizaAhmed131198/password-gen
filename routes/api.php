<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasswordController;

Route::post('/generate-password', [PasswordController::class, 'apiGenerate']);
