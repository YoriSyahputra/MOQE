<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [TicketController::class, 'dashboard'])->name('ticket.dashboard');
    Route::get('/create', [TicketController::class, 'create'])->name('ticket.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('ticket.store');
    Route::get('/showcase/{ticket}', [TicketController::class, 'show'])->name('ticket.show');
    Route::get('/{ticket}/edit', [TicketController::class, 'edit'])->name('ticket.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('ticket.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('ticket.destroy');
    Route::get('/settings', [UserController::class, 'settings'])->name('user.settings');
    Route::post('/settings/update', [UserController::class, 'updateSettings'])->name('user.settings.update');

    
    Route::post('/logout', [LoginController::class, 'logout'])->name('ticket.login');
});