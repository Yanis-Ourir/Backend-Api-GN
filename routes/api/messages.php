<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/messages', [MessageController::class, 'findAll']);
Route::get('/message/{id}', [MessageController::class, 'findById']);
Route::get('/messages/{user_id}', [MessageController::class, 'findByUserId']);
Route::post('/messages', [MessageController::class, 'create']);
Route::delete('/message/{id}', [MessageController::class, 'delete']);
