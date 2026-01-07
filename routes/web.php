<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Dosen\AttendanceController as DosenAttendanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ScanController;

Route::get('/', function () {
    return view('welcome');
});



// Dosen attendance routes
// Route::prefix('dosen')->group(function () {
//     Route::get('absensi', [DosenAttendanceController::class, 'index']);
//     Route::get('absensi/{course}', [DosenAttendanceController::class, 'show']);
//     Route::get('absensi/{course}/export', [DosenAttendanceController::class, 'exportXlsx']);
// });





Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', fn() => view('auth.register'))->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth', 'check_role:mahasiswa']], function () {
    Route::get('/verify', [VerificationController::class, 'index']);
    // verification POST now handled client-side against the API
    Route::get('/verify/{unique_id}', [VerificationController::class, 'show']);
    // verification PUT now handled client-side against the API
});

Route::group(['middleware' => ['auth', 'check_role:mahasiswa', 'check_status']], function () {
    Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
    Route::get('/scanner', [MahasiswaController::class, 'scanner']);
    // scan-result POST is now performed directly from the view to the API
});
Route::group(['middleware' => ['auth', 'check_role:admin,dosen']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::get('/attendance/{session}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('/absensi', [DosenAttendanceController::class, 'index']);
    Route::get('/absensi/{course}', [DosenAttendanceController::class, 'show']);
    Route::get('/absensi/{course}/export', [DosenAttendanceController::class, 'exportXlsx']);
    Route::get('/dosen/sessions', [DosenAttendanceController::class, 'sessionList'])->name('dosen.sessions');
});
Route::group(['middleware' => ['auth', 'check_role:admin']], function () {
    Route::get('/user',  fn() => 'Halaman User');
    // Admin user management
    Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
    Route::get('/admin/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
    // create/update/delete now handled via API from views
});
Route::get('/logout', [AuthController::class, 'logout']);
