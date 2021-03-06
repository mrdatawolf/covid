<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Charts;
use App\Http\Controllers\CountDailyController;
use \App\Http\Controllers\LocationController;
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

Route::get('/', [Charts::class, 'daily'])->name('dailygraph');

Route::get('/rawtable', function () {
    return view('rawtable');
})->name('rawtable');
Route::get('/dailygraph', [Charts::class, 'daily'])->name('dailygraph');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->resource('countdaily', CountDailyController::class);
Route::middleware(['auth:sanctum', 'verified'])->resource('location', LocationController::class);
