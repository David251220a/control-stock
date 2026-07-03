<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('inicio');
Route::get('/logout', [LoginController::class, 'logout']);

Auth::routes();

Route::group([
    'middleware' => 'auth',
], function(){

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categoria.index');

});

