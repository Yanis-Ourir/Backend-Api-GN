<?php


use App\Http\Controllers\EvaluationController;
use Illuminate\Support\Facades\Route;

Route::get('/evaluations', [EvaluationController::class, 'findAll']);
Route::get('/evaluation/{id}', [EvaluationController::class, 'findById']);
Route::get('/evaluations/game/{gameId}', [EvaluationController::class, 'findEvaluationsByGameId']);
Route::get('/evaluations/user/{userId}', [EvaluationController::class, 'findEvaluationsByUserId']);
Route::post('/evaluations', [EvaluationController::class, 'create'])->middleware('auth:api');
Route::put('/evaluation/{id}', [EvaluationController::class, 'update'])->middleware('auth:api');
Route::delete('/evaluation/{id}', [EvaluationController::class, 'delete'])->middleware('auth:api');

