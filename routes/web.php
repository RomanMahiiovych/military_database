<?php

use App\Http\Controllers\SoldierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;

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
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('soldiers', [SoldierController::class, 'index'])->name('soldiers.index')
    ->middleware('auth');

Route::get('soldiers/create', [SoldierController::class, 'create'])->name('soldiers.create')
    ->middleware('auth');

Route::post('soldiers/store', [SoldierController::class, 'store'])->name('soldiers.store')
    ->middleware('auth');

Route::get('soldiers/show/{id}', [SoldierController::class, 'show'])->name('soldiers.show')
    ->middleware('auth');

Route::get('soldiers/edit/{id}', [SoldierController::class, 'edit'])->name('soldiers.edit')
    ->middleware('auth');

Route::post('soldiers/update/{id}',  [SoldierController::class, 'update'])->name('soldiers.update')
    ->middleware('auth');

Route::delete('soldiers/destroy/{id}',  [SoldierController::class, 'destroy'])->name('soldiers.destroy')
    ->middleware('auth');

Route::get('heads/{rankId}',  [SoldierController::class, 'heads'])
    ->middleware('auth');
