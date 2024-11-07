<?php

use App\Http\Controllers\DislikeController;
use Illuminate\Support\Facades\Route;

Route::get('/dislikes', [DislikeController::class, 'findAll']);
Route::get('/dislike/{id}', [DislikeController::class, 'findById']);
Route::get('/dislike/{dislikeableId}/check-user/{userId}/type/{dislikeableType}', [DislikeController::class, 'checkIfUserAlreadyDisliked']);
Route::post('/dislikes', [DislikeController::class, 'create'])->middleware('auth:api');
Route::put('/dislike/{id}', [DislikeController::class, 'update'])->middleware('auth:api');
Route::delete('/dislike/{id}', [DislikeController::class, 'delete'])->middleware('auth:api');

