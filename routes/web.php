<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LotesController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\ProductosController;
use App\Http\Middleware\EnsureAuthenticated;

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/me', [AuthController::class, 'me']);

Route::middleware([EnsureAuthenticated::class])->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index']);

    // Lotes
    Route::get('/lotes',         [LotesController::class, 'index'])->name('lotes.index');
    Route::post('/lotes',        [LotesController::class, 'store'])->name('lotes.store');
    Route::put('/lotes/{batch}', [LotesController::class, 'update'])->name('lotes.update');

    // Proveedores
    Route::get('/proveedores',              [ProveedoresController::class, 'index'])->name('proveedores.index');
    Route::post('/proveedores',             [ProveedoresController::class, 'store'])->name('proveedores.store');
    Route::put('/proveedores/{supplier}',   [ProveedoresController::class, 'update'])->name('proveedores.update');

    // Productos
    Route::get('/productos',                [ProductosController::class, 'index'])->name('productos.index');
    Route::post('/productos',               [ProductosController::class, 'store'])->name('productos.store');
    Route::put('/productos/{producto}',     [ProductosController::class, 'update'])->name('productos.update');

    // Ventas
    Route::get('/ventas/historial',         [\App\Http\Controllers\VentasController::class, 'index'])->name('ventas.historial');
    Route::get('/ventas/nueva',             [\App\Http\Controllers\VentasController::class, 'create'])->name('ventas.create');
    Route::post('/ventas',                  [\App\Http\Controllers\VentasController::class, 'store'])->name('ventas.store');

    // Perfil
    Route::get('/perfil',                   [\App\Http\Controllers\ProfileController::class, 'index'])->name('perfil.index');
    Route::post('/perfil/password',         [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('perfil.password');

    // Descuentos
    Route::get('/descuentos',               [\App\Http\Controllers\DescuentosController::class, 'index'])->name('descuentos.index');
    Route::post('/descuentos',              [\App\Http\Controllers\DescuentosController::class, 'store'])->name('descuentos.store');
    Route::put('/descuentos/{discount}',    [\App\Http\Controllers\DescuentosController::class, 'update'])->name('descuentos.update');
    Route::post('/ventas/validar-descuento', [\App\Http\Controllers\VentasController::class, 'validateDiscount'])->name('ventas.validar-descuento');
});
