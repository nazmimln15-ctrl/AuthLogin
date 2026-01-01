<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use App\Models\Verification;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    /**
     * Handle QR scan result POST (expects JSON with key `qr_data`).
     */
    public function result(Request $request): JsonResponse
    {
        $data = $request->validate([
            'qr_data' => ['required', 'string'],
        ]);

        $token = session('api_token');
        if (! $token && Auth::check()) {
            /* @var User $user */
            $user = Auth::usder();
            $token = $user->createToken('frontend')->plainTextToken;
            session(['api_token' => $token]);
        }
        $resp = Http::withToken($token)->post(url('/api/admin/scan'), ['qr_data' => $data['qr_data']]);

        if ($resp->successful()) {
            return response()->json($resp->json(), $resp->status());
        }
        return response()->json($resp->json() ?: ['success' => false, 'body' => $resp->body()], $resp->status());
    }
}
