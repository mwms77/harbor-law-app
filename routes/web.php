<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientUploadController;
use App\Http\Controllers\IntakeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Client Routes (Authenticated Users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('client.dashboard');
    })->name('dashboard');

    // Home redirect
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');

    // Intake Form Routes
    Route::get('/intake', [IntakeController::class, 'index'])->name('intake.index');
    Route::post('/intake', [IntakeController::class, 'store'])->name('intake.store');

    // Client Document Upload Routes
    Route::get('/uploads', [ClientUploadController::class, 'index'])->name('uploads.index');
    Route::post('/uploads', [ClientUploadController::class, 'store'])->name('uploads.store');
    Route::get('/uploads/{upload}/download', [ClientUploadController::class, 'download'])->name('uploads.download');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Admin Middleware Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::patch('/users/{user}/status', [AdminController::class, 'updateStatus'])->name('admin.users.status');
    
    // Admin Notes
    Route::post('/users/{user}/notes', [AdminController::class, 'addNote'])->name('admin.users.notes.add');
    Route::delete('/notes/{note}', [AdminController::class, 'deleteNote'])->name('admin.users.notes.delete');
    
    // Upload Management
    Route::get('/uploads', [AdminController::class, 'uploads'])->name('admin.uploads');
    Route::get('/uploads/user/{user}', [AdminController::class, 'userUploads'])->name('admin.uploads.user');
    Route::get('/uploads/{upload}/download', [AdminController::class, 'downloadUpload'])->name('admin.uploads.download');
    Route::delete('/uploads/{upload}', [AdminController::class, 'deleteUpload'])->name('admin.uploads.delete');
    Route::get('/uploads/user/{user}/zip', [AdminController::class, 'downloadUserZip'])->name('admin.uploads.zip');
});
