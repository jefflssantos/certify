<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CredentialController;
use App\Http\Controllers\ModuleController;
use Illuminate\Support\Facades\Route;

Route::post('/login', LoginController::class);
Route::post('/register', RegisterController::class);

Route::apiResource('modules', ModuleController::class);
Route::as('modules')->apiResource('modules/{module}/credentials', CredentialController::class);
