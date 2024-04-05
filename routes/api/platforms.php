<?php

use App\Http\Controllers\PlatformController;
use Illuminate\Support\Facades\Route;

Route::get('/platforms', [PlatformController::class, 'findAll']);
Route::get('/platform/{id}', [PlatformController::class, 'findById']);
Route::get('/platform/{name}', [PlatformController::class, 'findByName']);
Route::post('/platforms', [PlatformController::class, 'create']);
Route::put('/platform/{id}', [PlatformController::class, 'update']);
Route::delete('/platform/{id}', [PlatformController::class, 'delete']);
