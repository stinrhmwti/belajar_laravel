<?php

use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DailyChecklistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

// Halaman utama diarahkan ke dashboard (kalau belum login, otomatis ke /login)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ============================================================
// SEMUA ROUTE DI BAWAH INI WAJIB LOGIN
// ============================================================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ============ VEHICLES ============
    Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');
    Route::get('/vehicles/{vehicle}/read-notification', [VehicleController::class, 'readNotification'])->name('vehicles.readNotification');

    // Teknisi & Admin boleh update status pemeliharaan cepat
    Route::middleware(['role:superadmin,admin,teknisi'])->group(function () {
        Route::put('/vehicles/{vehicle}/status', [VehicleController::class, 'updateStatus'])->name('vehicles.updateStatus');
    });

    // Hanya admin yang boleh tambah/edit/hapus data master kendaraan
    Route::middleware(['role:superadmin,admin'])->group(function () {
        Route::get('/vehicles-create', [VehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
        Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
        Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
        Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
    });

    // ============ DAILY CHECKLIST ============
    Route::middleware(['role:superadmin,admin,teknisi'])->group(function () {
        Route::get('/checklist', [DailyChecklistController::class, 'index'])->name('checklist.index');
        Route::get('/checklist/{checklist}', [DailyChecklistController::class, 'show'])->name('checklist.show');
        Route::get('/checklist-create', [DailyChecklistController::class, 'create'])->name('checklist.create');
        Route::post('/checklist', [DailyChecklistController::class, 'store'])->name('checklist.store');
        // Rute untuk menghapus checklist harian ditambahkan di sini
        Route::delete('/checklist/{checklist}', [DailyChecklistController::class, 'destroy'])->name('checklist.destroy');
        Route::put('/checklist/{checklist}/odometer', [DailyChecklistController::class, 'updateOdometer'])->name('checklist.updateOdometer');
    });

    // ============ EXPENSES (REKAP BIAYA) ============
    Route::middleware(['role:superadmin,admin,teknisi'])->group(function () {
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('/expenses-create', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    });

    // ============ VEHICLE HISTORIES (RIWAYAT SERVIS) ============
    Route::middleware(['role:superadmin,admin,teknisi'])->group(function () {
        Route::resource('vehicle-histories', \App\Http\Controllers\VehicleHistoryController::class);
    });

    // Hanya Admin, Super Admin, dan Pimpinan (Manager) yang boleh menyetujui/menolak anggaran perbaikan besar
    Route::middleware(['role:superadmin,admin,pimpinan'])->group(function () {
        Route::put('/expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    });

    // ============ KELUHAN / KENDALA KENDARAAN ============
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');

    Route::get('/complaints-create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    Route::middleware(['role:superadmin,teknisi'])->group(function () {
        Route::put('/complaints/{complaint}/status', [ComplaintController::class, 'updateStatus'])->name('complaints.updateStatus');
    });

    // ============ MANAJEMEN PENGGUNA (Super Admin & Admin Fleet only) ============
    Route::middleware(['role:superadmin,admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users-create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

// ============================================================
// ROUTE LOGIN / LOGOUT
// ============================================================
require __DIR__.'/auth.php';
