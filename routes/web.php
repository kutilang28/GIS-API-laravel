<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\LocationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/map', [MapController::class, 'index']);
Route::get('/detail', [MapController::class, 'detail']);
Route::get('/search', [MapController::class, 'index'])->name('search');
Route::post('/insert', [LocationController::class, 'store'])->name('insert');
Route::get('/get-locations', [LocationController::class, 'index']);

