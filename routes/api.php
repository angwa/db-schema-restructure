<?php

use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\ProvidersController;
use Illuminate\Support\Facades\Route;

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

Route::get('', function () {
    return response()->json([
        'status' => 200,
        'message' => 'Welcome!'
    ], 200);
});


Route::prefix('providers')->name('providers.')->group(function () {
    Route::get('', [ProvidersController::class, 'index'])->name('index');
    Route::post('', [ProvidersController::class, 'store'])->name('store');
    Route::put('{provider}', [ProvidersController::class, 'update'])->name('update');
    Route::delete('{provider}', [ProvidersController::class, 'delete'])->name('delete');
});

Route::prefix('articles')->name('articles.')->group(function () {
    Route::get('', [ArticlesController::class, 'index'])->name('index');
    Route::post('', [ArticlesController::class, 'store'])->name('store');
    Route::put('{article}', [ArticlesController::class, 'update'])->name('update');
    Route::delete('{article}', [ArticlesController::class, 'delete'])->name('delete');
});
