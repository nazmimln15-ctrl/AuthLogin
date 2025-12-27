<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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

        $qr = $data['qr_data'];

        try {
            $user = Auth::user();

            // Expect qr_data to be a JSON payload with session_token
            $payload = json_decode($qr, true);
            $sessionToken = $payload['session_token'] ?? null;

            if (! $sessionToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payload QR tidak valid',
                ], 422);
            }

            $session = AttendanceSession::where('token', $sessionToken)->first();

            if (! $session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sesi absensi tidak ditemukan',
                ], 404);
            }

            // Prevent duplicate attendance for same user & session
            $existing = Attendance::where('attendance_session_id', $session->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => true,
                    'message' => 'Anda sudah terdaftar hadir',
                    'attendance_id' => $existing->id,
                ]);
            }

            $attendance = Attendance::create([
                'attendance_session_id' => $session->id,
                'user_id' => $user->id,
                'qr_token' => $sessionToken,
                'status' => 'present',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dicatat',
                'attendance_id' => $attendance->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
