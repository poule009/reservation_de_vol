<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\PaymentController;

// Page d'accueil (redirection vers login ou dashboard)
Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard') 
        : redirect()->route('register');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});













// Routes d'authentification (générées par Breeze)
require __DIR__.'/auth.php';

// Routes protégées (nécessitent authentification)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Vols
    Route::prefix('flights')->name('flights.')->group(function () {
        Route::get('/', [FlightController::class, 'index'])->name('index');
        Route::get('/search', [FlightController::class, 'search'])->name('search');
        Route::get('/{flight}', [FlightController::class, 'show'])->name('show');
        Route::post('/sync', [FlightController::class, 'sync'])->name('sync');
    });
    
    // Réservations
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/create/{flight}', [ReservationController::class, 'create'])->name('create');
        Route::post('/{flight}', [ReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [ReservationController::class, 'edit'])->name('edit');
        Route::patch('/{reservation}', [ReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
    });
    
    // Sièges
    Route::prefix('seats')->name('seats.')->group(function () {
        Route::get('/{reservation}/select', [SeatController::class, 'select'])->name('select');
        Route::post('/{reservation}', [SeatController::class, 'store'])->name('store');
        
        // API pour temps réel
        Route::get('/{reservation}/available', [SeatController::class, 'available'])->name('available');
        Route::post('/{seat}/hold', [SeatController::class, 'hold'])->name('hold');
        Route::post('/{seat}/release', [SeatController::class, 'release'])->name('release');
    });
    
    // Paiements
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/{reservation}', [PaymentController::class, 'create'])->name('create');
        Route::post('/{reservation}', [PaymentController::class, 'store'])->name('store');
        Route::get('/{reservation}/success', [PaymentController::class, 'success'])->name('success');
        Route::get('/{reservation}/ticket', [PaymentController::class, 'downloadTicket'])->name('ticket');
    });
});













require __DIR__.'/auth.php';
