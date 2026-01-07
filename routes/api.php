<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

// Token issuance endpoint (convenience): POST /api/token with email+password returns a personal access token.
Route::post('token', function (Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);
    $user = App\Models\User::where('email', $request->email)->first();
    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
    $token = $user->createToken('script')->plainTextToken;
    return response()->json(['token' => $token]);
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes return JSON and are intended to be consumed by frontend
| controllers in this project. For simplicity these routes are public
| (no auth) so server-side/frontend controllers can call them.
|
*/

// Use Sanctum token auth for API endpoints. Ensure laravel/sanctum is installed and configured.
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    // Users
    Route::get('users', [App\Http\Controllers\Backend\UserController::class, 'index']);
    Route::post('users', [App\Http\Controllers\Backend\UserController::class, 'store']);
    Route::get('users/{user}', [App\Http\Controllers\Backend\UserController::class, 'show']);
    Route::put('users/{user}', [App\Http\Controllers\Backend\UserController::class, 'update']);
    Route::delete('users/{user}', [App\Http\Controllers\Backend\UserController::class, 'destroy']);

    // Attendance sessions
    Route::get('sessions', [App\Http\Controllers\Backend\AttendanceSessionController::class, 'index']);
    Route::post('sessions', [App\Http\Controllers\Backend\AttendanceSessionController::class, 'store']);
    Route::get('sessions/{session}', [App\Http\Controllers\Backend\AttendanceSessionController::class, 'show']);
    Route::delete('sessions/{session}', [App\Http\Controllers\Backend\AttendanceSessionController::class, 'destroy']);

    // Attendances
    Route::get('sessions/{session}/attendances', [App\Http\Controllers\Backend\AttendanceController::class, 'index']);
    Route::post('attendances', [App\Http\Controllers\Backend\AttendanceController::class, 'store']);
    Route::get('attendances/{attendance}', [App\Http\Controllers\Backend\AttendanceController::class, 'show']);
    Route::delete('attendances/{attendance}', [App\Http\Controllers\Backend\AttendanceController::class, 'destroy']);

    // Scan endpoint
    Route::post('scan', [App\Http\Controllers\Backend\ScanController::class, 'result']);

    // Verification endpoints
    Route::post('verification', [App\Http\Controllers\Backend\VerificationController::class, 'store']);
    Route::put('verification/{unique_id}', [App\Http\Controllers\Backend\VerificationController::class, 'update']);
});

// Convenience endpoint to return the authenticated user when using token auth.
Route::middleware('auth:sanctum')->get('me', function (Illuminate\Http\Request $request) {
    return response()->json(['data' => $request->user()]);
});
