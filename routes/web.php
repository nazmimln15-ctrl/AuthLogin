<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VerificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', fn() => view('auth.login')) -> name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', fn() => view('auth.register')) -> name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth', 'check_role:mahasiswa']], function () {
    Route::get('/verify', [VerificationController::class, 'index']);
    // Route::get('/send-otp', [VerificationController::class, 'send_otp']);
    Route::post('/verify', [VerificationController::class, 'store']);
    Route::get('/verify/{unique_id}', [VerificationController::class, 'show']);
    Route::put('/verify/{unique_id}', [VerificationController::class, 'update']);
});

Route::group(['middleware' => ['auth', 'check_role:mahasiswa', 'check_status']], function () {
    Route::get('/mahasiswa', fn() => 'Halaman Mahasiswa');
});
Route::group(['middleware' => ['auth', 'check_role:admin,dosen']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
Route::group(['middleware' => ['auth', 'check_role:admin']], function () {
    Route::get('/user',  fn() => 'Halaman User');
});
Route::get('/logout', [AuthController::class, 'logout']);
