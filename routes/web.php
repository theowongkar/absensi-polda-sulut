<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Dashboard\AttendanceController as DashboardAttendanceController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EmployeeController as DashboardEmployeeController;

Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->middleware('throttle:5,5')->name('authenticate');
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('throttle:5,5')->name('logout');

    // Profile  
    Route::get('/profil-saya', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil-saya', [ProfileController::class, 'update'])->middleware('throttle:5,5')->name('profile.update');

    // Default URL
    Route::get('/', fn() => redirect()->route('dashboard'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dashboard Pegawai
    Route::get('/dashboard/pegawai', [DashboardEmployeeController::class, 'index'])->name('dashboard.employee.index');
    Route::get('/dashboard/pegawai/tambah', [DashboardEmployeeController::class, 'create'])->name('dashboard.employee.create');
    Route::post('/dashboard/pegawai/tambah', [DashboardEmployeeController::class, 'store'])->middleware('throttle:10,5')->name('dashboard.employee.store');
    Route::get('/dashboard/pegawai/{nrp}/ubah', [DashboardEmployeeController::class, 'edit'])->name('dashboard.employee.edit');
    Route::put('/dashboard/pegawai/{nrp}/ubah', [DashboardEmployeeController::class, 'update'])->middleware('throttle:10,5')->name('dashboard.employee.update');
    Route::delete('/dashboard/pegawai/{nrp}/hapus', [DashboardEmployeeController::class, 'destroy'])->middleware('throttle:10,5')->name('dashboard.employee.destroy');

    // Dashboard Presensi Saya
    Route::get('/dashboard/presensi-saya', [DashboardAttendanceController::class, 'myAttendanceIndex'])->name('dashboard.my-attendance.index');
    Route::get('/dashboard/input-presensi', [DashboardAttendanceController::class, 'myAttendanceCreate'])->name('dashboard.my-attendance.create');
    Route::post('/dashboard/input-presensi', [DashboardAttendanceController::class, 'myAttendanceStore'])->name('dashboard.my-attendance.store');

    // Dashboard Presensi
    Route::get('/dashboard/presensi', [DashboardAttendanceController::class, 'index'])->name('dashboard.attendance.index');
    Route::get('/dashboard/presensi/{nrp}/lihat', [DashboardAttendanceController::class, 'show'])->name('dashboard.attendance.show');
    Route::post('/dashboard/presensi/{nrp}/tambah', [DashboardAttendanceController::class, 'store'])->name('dashboard.attendance.store');
    Route::put('/dashboard/presensi/{nrp}/{attendanceId}/ubah', [DashboardAttendanceController::class, 'update'])->name('dashboard.attendance.update');
    Route::delete('/dashboard/presensi/{nrp}/{attendanceId}/hapus', [DashboardAttendanceController::class, 'destroy'])->name('dashboard.attendance.destroy');
});
