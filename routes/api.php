<?php

use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

require __DIR__ . '/api/games.php';
require __DIR__ . '/api/users.php';
require __DIR__ . '/api/reviews.php';
require __DIR__ . '/api/evaluations.php';
require __DIR__ . '/api/gamelists.php';
require __DIR__ . '/api/tags.php';
require __DIR__ . '/api/platforms.php';
require __DIR__ . '/api/messages.php';
require __DIR__ . '/api/likes.php';
require __DIR__ . '/api/dislikes.php';
require __DIR__ . '/api/auth.php';





