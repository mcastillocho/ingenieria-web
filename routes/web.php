<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Middleware\EnsureAuthenticated;

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/me', [AuthController::class, 'me']);

Route::middleware([EnsureAuthenticated::class])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    // Inventario
    Route::get('/inventario',              [InventarioController::class, 'index'])->name('inventario.index');
    Route::post('/inventario',             [InventarioController::class, 'store'])->name('inventario.store');
    Route::put('/inventario/{batch}',      [InventarioController::class, 'update'])->name('inventario.update');

    // Proveedores
    Route::get('/proveedores',              [ProveedoresController::class, 'index'])->name('proveedores.index');
    Route::post('/proveedores',             [ProveedoresController::class, 'store'])->name('proveedores.store');
    Route::put('/proveedores/{supplier}',   [ProveedoresController::class, 'update'])->name('proveedores.update');

});
