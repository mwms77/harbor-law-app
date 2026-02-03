<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IntakeController;
use App\Http\Controllers\EstatePlanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // User management
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::delete('/users/{user}/force', [AdminUserController::class, 'forceDestroy'])->name('admin.users.force-destroy');
    Route::get('/users/{user}/download-intake', [AdminUserController::class, 'downloadIntake'])->name('admin.users.download-intake');
    Route::post('/users/{user}/upload-plan', [AdminUserController::class, 'uploadPlan'])->name('admin.users.upload-plan');
    Route::delete('/users/{user}/plans/{estatePlan}', [AdminUserController::class, 'deletePlan'])->name('admin.users.delete-plan');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle-status');
    
    // Settings
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings/logo', [AdminSettingsController::class, 'uploadLogo'])->name('admin.settings.upload-logo');
    Route::delete('/settings/logo', [AdminSettingsController::class, 'deleteLogo'])->name('admin.settings.delete-logo');
    
    // Profile
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
});

// User routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Intake form
    Route::get('/intake', [IntakeController::class, 'show'])->name('intake.show');
    Route::post('/intake/personal-info', [IntakeController::class, 'savePersonalInfo'])->name('intake.save-personal-info');
    Route::post('/intake/spouse-info', [IntakeController::class, 'saveSpouseInfo'])->name('intake.save-spouse-info');
    Route::post('/intake/children', [IntakeController::class, 'saveChildren'])->name('intake.save-children');
    Route::post('/intake/assets', [IntakeController::class, 'saveAssets'])->name('intake.save-assets');
    Route::post('/intake/liabilities', [IntakeController::class, 'saveLiabilities'])->name('intake.save-liabilities');
    Route::post('/intake/submit', [IntakeController::class, 'submit'])->name('intake.submit');
    Route::get('/intake/download', [IntakeController::class, 'download'])->name('intake.download');
    
    // Estate plan downloads
    Route::get('/estate-plans/{estatePlan}/download', [EstatePlanController::class, 'download'])->name('estate-plans.download');
});
