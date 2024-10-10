<?php

use App\Http\Controllers\GameListController;
use Illuminate\Support\Facades\Route;

Route::get('/game-lists', [GameListController::class, 'findAll']);
Route::get('/game-list/{id}', [GameListController::class, 'findById']);
Route::get('/game-list/{name}', [GameListController::class, 'findByName']);
Route::get('/game-lists/user/{userId}', [GameListController::class, 'findGameListByUserId']);
Route::get('/game-list/{userId}/game/{gameId}', [GameListController::class, 'checkIfGameIsAlreadyInTheList']);
Route::get('/game-lists/most-liked/{limit}', [GameListController::class, 'findMostLikedList']);
Route::post('/game-lists', [GameListController::class, 'createList']);
Route::post('/game-lists/add-game', [GameListController::class, 'addGame']);
Route::put('/game-list/{id}', [GameListController::class, 'update']);
Route::delete('/game-list/{id}', [GameListController::class, 'delete']);
Route::delete('/game-lists/remove-game', [GameListController::class, 'removeGameFromList']);


