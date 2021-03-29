<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaingaugesController;
use App\Http\Controllers\RainfalldatasController;
use App\Http\Controllers\RainfalleventsController;

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
    return view('index');
});
Route::resource('/raingauges', RaingaugesController::class);

Route::get('/rainfalldatas/import', [RainfalldatasController::class, 'import'])->name('rainfalldatas.import');
Route::get('/rainfalldatas/{id}/simulate/', [RainfalldatasController::class, 'simulate'])->name('simulate');
Route::post('/rainfalldatas/saveimport', [RainfalldatasController::class, 'saveimport'])->name('saveimport');
Route::resource('/rainfalldatas', RainfalldatasController::class);
Route::resource('/rainfallevents', RainfalleventsController::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
