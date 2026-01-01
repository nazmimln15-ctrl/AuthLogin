<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index($sessionId)
    {
        $session = AttendanceSession::findOrFail($sessionId);
        $rows = Attendance::where('attendance_session_id', $session->id)
            ->with('user')
            ->get();
        return response()->json(['success' => true, 'data' => ['session' => $session, 'rows' => $rows]]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance_session_id' => ['required', 'integer'],
            'qr_token' => ['nullable', 'string'],
            'user_id' => ['nullable', 'integer'],
        ]);

        $userId = $data['user_id'] ?? Auth::id();
        $session = AttendanceSession::findOrFail($data['attendance_session_id']);

        $existing = Attendance::where('attendance_session_id', $session->id)->where('user_id', $userId)->first();
        if ($existing) {
            return response()->json(['success' => true, 'message' => 'Already present', 'attendance_id' => $existing->id]);
        }

        $attendance = Attendance::create([
            'attendance_session_id' => $session->id,
            'user_id' => $userId,
            'qr_token' => $data['qr_token'] ?? $session->token,
            'status' => 'present',
        ]);

        return response()->json(['success' => true, 'data' => $attendance], 201);
    }

    public function show(Attendance $attendance)
    {
        return response()->json(['success' => true, 'data' => $attendance->load('user')]);
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return response()->json(['success' => true]);
    }
}
