<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/games', [GameController::class, 'findAll']);
Route::get('game/{column}/{name}', [GameController::class, 'findByColumn']);
Route::get('/games/{id}', [GameController::class, 'findById'])->where(['id' => '[0-9]+']);
Route::get('/games/rating', [GameController::class, 'findFirstTenMostRatedGames']);
Route::post('/games', [GameController::class, 'create']);
Route::put('/game/{id}', [GameController::class, 'update']);
Route::delete('/game/{id}', [GameController::class, 'delete']);


