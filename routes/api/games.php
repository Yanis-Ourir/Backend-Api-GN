<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/games', [GameController::class, 'findAll']);
Route::get('/game/{id}', [GameController::class, 'findById']);
Route::get('/game/{name}', [GameController::class, 'findByName']);
Route::post('/games', [GameController::class, 'create']);
Route::put('/game/{id}', [GameController::class, 'update']);
Route::delete('/game/{id}', [GameController::class, 'delete']);


