<?php

use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;

Route::get('/likes', [LikeController::class, 'findAll']);
Route::get('/like/{id}', [LikeController::class, 'findById']);
Route::get('/likes/{user_id}', [LikeController::class, 'findByUserId']);
Route::get('/like/{likeableId}/check-user/{userId}/type/{likeableType}', [LikeController::class, 'checkIfUserAlreadyLiked']);
Route::post('/likes', [LikeController::class, 'create']);
Route::delete('/like/{id}', [LikeController::class, 'delete']);

