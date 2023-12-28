<?php

use App\Http\Controllers\Web\NoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes();

Route::middleware('auth')->get('/home', [\App\Http\Controllers\Web\HomeController::class, 'index'])->name('home');
Route::middleware('auth')->put('/notes/{id}/update', [NoteController::class, 'update'])->name('update.note');
Route::middleware('auth')->post('/notes/add', [NoteController::class, 'storeAjax'])->name('add.note');
Route::middleware('auth')->post('/notes', [NoteController::class, 'store'])->name('create.note');
Route::middleware('auth')->get('/notes/create', [NoteController::class, 'create'])->name('create.notes');
