<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Donor\DashboardController as DonorDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\BloodRequest\BloodRequestController;
use App\Http\Controllers\Donor\DonorController;
use App\Http\Controllers\Emergency\EmergencyController;
use App\Http\Controllers\CenterController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Donor\DonorAppointmentController;
use App\Http\Controllers\Donor\DonorEmergencyRequestController;
use App\Http\Controllers\Donor\DonorRewardController;
use App\Http\Controllers\Donor\DonorBadgeController;

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
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Settings Routes
    Route::get('/settings', [SettingsController::class, 'show'])->name('settings.show');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
        
        // Blood Centers
        Route::get('/centers', [App\Http\Controllers\Admin\CenterController::class, 'index'])->name('centers');
        Route::get('/centers/create', [App\Http\Controllers\Admin\CenterController::class, 'create'])->name('centers.create');
        Route::post('/centers', [App\Http\Controllers\Admin\CenterController::class, 'store'])->name('centers.store');
        Route::get('/centers/{center}/edit', [App\Http\Controllers\Admin\CenterController::class, 'edit'])->name('centers.edit');
        Route::put('/centers/{center}', [App\Http\Controllers\Admin\CenterController::class, 'update'])->name('centers.update');
        Route::delete('/centers/{center}', [App\Http\Controllers\Admin\CenterController::class, 'destroy'])->name('centers.destroy');
        
        // Events Management
        Route::get('/events', [App\Http\Controllers\Admin\EventController::class, 'index'])->name('events');
        Route::get('/events/create', [App\Http\Controllers\Admin\EventController::class, 'create'])->name('events.create');
        Route::post('/events', [App\Http\Controllers\Admin\EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [App\Http\Controllers\Admin\EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('events.destroy');
        
        // Statistics
        Route::get('/statistics', [App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('statistics');
        Route::get('/statistics/donations', [App\Http\Controllers\Admin\StatisticsController::class, 'donations'])->name('statistics.donations');
        Route::get('/statistics/users', [App\Http\Controllers\Admin\StatisticsController::class, 'users'])->name('statistics.users');
        Route::get('/statistics/centers', [App\Http\Controllers\Admin\StatisticsController::class, 'centers'])->name('statistics.centers');
        Route::get('/statistics/events', [App\Http\Controllers\Admin\StatisticsController::class, 'events'])->name('statistics.events');
        
        // Reports
        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
        Route::get('/reports/donations', [App\Http\Controllers\Admin\ReportController::class, 'donations'])->name('reports.donations');
        Route::get('/reports/blood-stock', [App\Http\Controllers\Admin\ReportController::class, 'bloodStock'])->name('reports.blood-stock');
        Route::get('/reports/users', [App\Http\Controllers\Admin\ReportController::class, 'users'])->name('reports.users');
        Route::get('/reports/export/{type}', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
        
        // Blood Stock Management
        Route::get('/blood-stock', [App\Http\Controllers\Admin\BloodStockController::class, 'index'])->name('blood-stock');
        Route::put('/blood-stock/{bloodStock}', [App\Http\Controllers\Admin\BloodStockController::class, 'update'])->name('blood-stock.update');
        Route::post('/blood-stock/update-all', [App\Http\Controllers\Admin\BloodStockController::class, 'updateAll'])->name('blood-stock.update-all');

        // Blood Requests Management
        Route::get('/blood-requests', [App\Http\Controllers\Admin\BloodRequestController::class, 'index'])->name('blood-requests');
        Route::post('/blood-requests/{id}/accept', [App\Http\Controllers\Admin\BloodRequestController::class, 'accept'])->name('blood-requests.accept');
        Route::post('/blood-requests/{id}/reject', [App\Http\Controllers\Admin\BloodRequestController::class, 'reject'])->name('blood-requests.reject');
    });

    // Donor Routes
    Route::middleware(['role:donor'])->prefix('donor')->name('donor.')->group(function () {
        Route::get('/dashboard', [DonorDashboardController::class, 'index'])->name('dashboard');
        
        // Appointments
        Route::get('/appointments', [DonorAppointmentController::class, 'index'])->name('appointments');
        Route::get('/appointments/create', [DonorAppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [DonorAppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/appointments/{appointment}', [DonorAppointmentController::class, 'show'])->name('appointments.show');
        Route::put('/appointments/{appointment}/cancel', [DonorAppointmentController::class, 'cancel'])->name('appointments.cancel');
        
        // Emergency Requests
        Route::get('/emergency-requests', [DonorEmergencyRequestController::class, 'index'])->name('emergency-requests');
        Route::get('/emergency-requests/{emergencyRequest}', [DonorEmergencyRequestController::class, 'show'])->name('emergency-requests.show');
        Route::post('/emergency-requests/{emergencyRequest}/respond', [DonorEmergencyRequestController::class, 'respond'])->name('emergency-requests.respond');
        
        // Rewards
        Route::get('/rewards', [DonorRewardController::class, 'index'])->name('rewards');
        Route::get('/badges', [DonorBadgeController::class, 'index'])->name('badges');
    });

    // Patient Routes
    Route::middleware(['role:patient'])->prefix('patient')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])
             ->name('patient.dashboard');
    });

    // Centers Routes
    Route::get('/centers/nearby', [CenterController::class, 'nearby'])->name('centers.nearby');
    Route::get('/centers', [CenterController::class, 'index'])->name('centers.index');
    Route::post('/centers/search', [CenterController::class, 'search'])->name('centers.search');

    // Donor Routes
    Route::get('/donors/search', [App\Http\Controllers\Donor\DonorController::class, 'search'])->name('donors.search');
});

// Patient Routes
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/patient/dashboard', function () {
        $data = [
            'activeRequests' => \App\Models\BloodRequest::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->count(),
            'availableDonors' => \App\Models\User::where('role', 'donor')
                ->where('last_donation_at', '<=', now()->subMonths(3))
                ->count(),
            'notifications' => \App\Models\Notification::where('user_id', Auth::id())
                ->where('read', false)
                ->count(),
            'recentRequests' => \App\Models\BloodRequest::where('user_id', Auth::id())
                ->latest()
                ->take(5)
                ->get()
        ];
        return view('patient.patientdashboard', $data);
    })->name('patient.dashboard');

    Route::post('/blood-requests/store', [BloodRequestController::class, 'store'])->name('blood-requests.store');
    Route::get('/donors/search', [DonorController::class, 'search'])->name('donors.search');
    Route::post('/emergency/alert', [EmergencyController::class, 'alert'])->name('emergency.alert');
    Route::post('/notifications/mark-read', [App\Http\Controllers\Patient\NotificationController::class, 'markAllRead'])->name('patient.notifications.markRead');
    Route::get('/notifications', [App\Http\Controllers\Patient\NotificationController::class, 'index'])->name('patient.notifications');
    Route::post('/notifications/{notification}/mark-read', [App\Http\Controllers\Patient\NotificationController::class, 'markRead'])->name('patient.notifications.markReadOne');
});