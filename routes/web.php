<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Donor\DashboardController as DonorDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return view('landing');
})->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });

    // Donor Routes
    Route::middleware(['role:donor'])->prefix('donor')->group(function () {
        Route::get('/dashboard', [DonorDashboardController::class, 'index'])
             ->name('donor.dashboard');
    });

    // Patient Routes
    Route::middleware(['role:patient'])->prefix('patient')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])
             ->name('patient.dashboard');
    });
});