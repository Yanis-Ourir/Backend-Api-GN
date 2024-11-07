<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/users', [UserController::class, 'findAll']);
Route::get('/users/{id}', [UserController::class, 'findByUserId']);
Route::get('/user/{name}', [UserController::class, 'findByPseudo']);
Route::get('/users/{userId}/evaluation/{gameId}', [UserController::class, 'findUsersWhoRatedSameGames']);
Route::post('/register', [UserController::class, 'create']);
Route::post('/users/{id}', [UserController::class, 'update'])->middleware('auth:api');
Route::delete('/users/{id}', [UserController::class, 'delete']);
