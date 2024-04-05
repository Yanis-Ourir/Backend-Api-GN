<?php

use App\Http\Controllers\DislikeController;
use Illuminate\Support\Facades\Route;

Route::get('/dislikes', [DislikeController::class, 'findAll']);
Route::get('/dislike/{id}', [DislikeController::class, 'findById']);
Route::post('/dislikes', [DislikeController::class, 'create']);
Route::put('/dislike/{id}', [DislikeController::class, 'update']);
Route::delete('/dislike/{id}', [DislikeController::class, 'delete']);

