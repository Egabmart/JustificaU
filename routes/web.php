<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\JustificacionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTAS PÚBLICAS (para visitantes) ---
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/acerca', [PageController::class, 'about'])->name('about');
Route::get('/contacto', [PageController::class, 'contact'])->name('contact');


// --- RUTAS PARA USUARIOS AUTENTICADOS ---
// Todo lo que está dentro de este grupo requerirá que el usuario inicie sesión.
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // LA LÍNEA CLAVE ESTÁ AQUÍ.
    // Movemos el CRUD de justificaciones DENTRO de este grupo protegido.
    Route::resource('justificaciones', JustificacionController::class);
});


// --- RUTAS DE AUTENTICACIÓN (GENERADAS POR BREEZE) ---
require __DIR__.'/auth.php';