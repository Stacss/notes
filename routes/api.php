<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('notes', NoteController::class);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);
