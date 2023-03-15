<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [IndexController::class, 'index'])->name('index');
Route::get('/store', [IndexController::class, 'store'])->name('store');
Route::post('/create', [IndexController::class, 'create'])->name('create');
Route::get('download/{type}/{file}', [IndexController::class, 'download'])->name('download');
