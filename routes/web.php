<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MapaController;
use App\Http\Controllers\MmaRegistrationController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserPaymentController;

// Página principal Copa Índigo MMA
Route::get('/', function () {
    return view('home');
})->name('home');

// Boleto digital público (escaneable)
Route::get('/entrada/{token}', [TicketController::class, 'show'])->name('ticket.show');

// Mapa interactivo de mesas
Route::get('/mapa', [MapaController::class, 'index'])->name('mapa.index');
Route::post('/mapa/reservar', [MapaController::class, 'reservar'])->name('mapa.reservar');

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Compra de entradas (requiere login)
Route::middleware('auth')->group(function () {
    Route::post('/registro', [MmaRegistrationController::class, 'store'])->name('mma.register');
    Route::get('/mis-registros', [UserPaymentController::class, 'index'])->name('user.registrations');
});

// Panel de administración
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.mma.index');
    })->name('dashboard');

    Route::get('/registros', [MmaRegistrationController::class, 'adminIndex'])->name('mma.index');
    Route::patch('/registros/{registration}/status', [MmaRegistrationController::class, 'updateStatus'])->name('mma.update-status');
    Route::delete('/registros/{registration}', [MmaRegistrationController::class, 'destroy'])->name('mma.destroy');
    Route::get('/escaner', [ScannerController::class, 'show'])->name('scanner');

    Route::get('/whatsapp/{notification}/link', [MmaRegistrationController::class, 'whatsappLink'])->name('whatsapp.link');
    Route::patch('/whatsapp/{notification}/sent', [MmaRegistrationController::class, 'markWhatsappSent'])->name('whatsapp.sent');
    Route::patch('/whatsapp/{notification}/failed', [MmaRegistrationController::class, 'markWhatsappFailed'])->name('whatsapp.failed');
});
