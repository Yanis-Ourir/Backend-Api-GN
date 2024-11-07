<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/reviews', [ReviewController::class, 'findAll']);
Route::get('/review/{id}', [ReviewController::class, 'findById']);
Route::post('/reviews', [ReviewController::class, 'create'])->middleware('auth:api');
Route::put('/review/{id}', [ReviewController::class, 'update']);
Route::delete('/review/{id}', [ReviewController::class, 'delete']);

