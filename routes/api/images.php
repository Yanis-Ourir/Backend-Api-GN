<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/images', [ImageController::class, 'findAll']);
Route::get('/image/{id}', [ImageController::class, 'findById']);
Route::post('/images', [ImageController::class, 'create']);
Route::put('/image/{id}', [ImageController::class, 'update']);
Route::delete('/image/{id}', [ImageController::class, 'delete']);

