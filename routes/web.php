<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LimpiarController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('inicio');
Route::get('/logout', [LoginController::class, 'logout']);
Route::get('/limpiar', [LimpiarController::class, 'limpiar'])->name('limpiar');

Auth::routes();

Route::group([
    'middleware' => 'auth',
], function(){

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categoria.index');
    Route::get('/productos', [ProductoController::class, 'index'])->name('producto.index');
    Route::get('/carrito-compra', [ProductoController::class, 'carrito'])->name('producto.carrito');

    Route::get('/factura', [FacturaController::class, 'index'])->name('factura.index');
    Route::get('/factura/{factura}/ver', [FacturaController::class, 'show'])->name('factura.show');
    Route::get('/factura/{factura}/factura', [FacturaController::class, 'factura'])->name('factura.factura');

    Route::get('/productos-listado', [ProductoController::class, 'listado'])->name('producto.listado');

});

