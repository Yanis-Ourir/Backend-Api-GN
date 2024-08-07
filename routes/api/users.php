<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/users', [UserController::class, 'findAll']);
Route::get('/users/{id}', [UserController::class, 'findById']);
Route::get('/user/{name}', [UserController::class, 'findByPseudo']);
Route::post('/register', [UserController::class, 'create']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'delete']);
