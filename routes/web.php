<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RaingaugesController;
use App\Http\Controllers\RainfalldatasController;
use App\Http\Controllers\RainfalleventsController;
use App\Http\Controllers\StudysitesController;
use App\Http\Controllers\SimulationsController;

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
Route::resource('/studysites', StudysitesController::class);
Route::get('/rainfalldatas/import', [RainfalldatasController::class, 'import'])->name('rainfalldatas.import');
//Route::get('/rainfalldatas/{id}/simulate/', [RainfalldatasController::class, 'simulate'])->name('simulate');
//Route::get('/rainfalldatas/simulations', [RainfalldatasController::class, 'simulations'])->name('rainfalldatas.simulations');
Route::post('/rainfalldatas/saveimport', [RainfalldatasController::class, 'saveimport'])->name('saveimport');
Route::resource('/rainfalldatas', RainfalldatasController::class);
Route::resource('/simulations', SimulationsController::class);
Route::resource('/rainfallevents', RainfalleventsController::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
