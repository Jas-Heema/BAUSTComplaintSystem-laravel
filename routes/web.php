<?php

use App\Http\Controllers\AdminComplaintController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'))->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/attachments/{attachment}', [ComplaintController::class, 'downloadAttachment'])->name('attachments.download');

    Route::middleware('role:student')->group(function () {
        Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
        Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
        Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    });

    Route::middleware('role:cse_admin,hall_admin,library_admin,proctor,super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
        Route::patch('/complaints/{complaint}', [AdminComplaintController::class, 'update'])->name('complaints.update');
    });

    Route::middleware('role:super_admin')->prefix('system')->name('superadmin.')->group(function () {
        Route::get('/categories', [SuperAdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [SuperAdminController::class, 'saveCategory'])->name('categories.store');
        Route::put('/categories/{category}', [SuperAdminController::class, 'saveCategory'])->name('categories.update');
        Route::get('/administrators', [SuperAdminController::class, 'administrators'])->name('administrators');
        Route::post('/administrators', [SuperAdminController::class, 'storeAdministrator'])->name('administrators.store');
        Route::patch('/administrators/{user}/toggle', [SuperAdminController::class, 'toggleAdministrator'])->name('administrators.toggle');
    });
});
