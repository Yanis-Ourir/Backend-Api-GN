<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/tags', [TagController::class, 'findAll']);
Route::get('/tag/{id}', [TagController::class, 'findById']);
Route::get('/tag/{name}', [TagController::class, 'findByName']);
Route::post('/tags', [TagController::class, 'create']);
Route::put('/tag/{id}', [TagController::class, 'update']);
Route::delete('/tag/{id}', [TagController::class, 'delete']);
