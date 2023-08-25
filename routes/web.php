<?php

use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\DatabaseController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashBoardController::class,'dashboard'])->name('dashboard');

    Route::post('/database/test-connection', [DatabaseController::class,'testConnection'])->name('database.test-connection');
    Route::post('/database/store', [DatabaseController::class,'store'])->name('database.store');
    Route::get('/database/{database}', [DatabaseController::class,'show'])->name('database.show');
    Route::post('/database/{database}/ask-to-gpt', [DatabaseController::class,'askToGPT'])->name('database.ask-to-gpt');
    Route::post('/database/{database}/save-query', [DatabaseController::class,'saveToReport'])->name('database.save-query');
});
