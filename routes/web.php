<?php

use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/', [HomeController::class, 'index']);


Route::resource('store', StoreController::class)->middleware('auth'); //aqui ponemos que en esta ruta
                                                                                               //este el midlewere auth
Auth::routes(); //rutas de autentificacion de laravel las cuales van a los archivos de views/auth y views/auth/passwords

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
