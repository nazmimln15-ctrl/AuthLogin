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
        return response()->json(['message' => 'This endpoint is deprecated. Call /api/admin/scan from the frontend.'], 410);
    }
}
