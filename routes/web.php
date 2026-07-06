<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MmaRegistrationController;

// Página principal Copa Índigo MMA
Route::get('/', function () {
    return view('home');
})->name('home');

// Registro público de entradas
Route::post('/registro', [MmaRegistrationController::class, 'store'])->name('mma.register');

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Panel de administración
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.mma.index');
    })->name('dashboard');

    Route::get('/registros', [MmaRegistrationController::class, 'adminIndex'])->name('mma.index');
    Route::patch('/registros/{registration}/status', [MmaRegistrationController::class, 'updateStatus'])->name('mma.update-status');
    Route::delete('/registros/{registration}', [MmaRegistrationController::class, 'destroy'])->name('mma.destroy');
});
