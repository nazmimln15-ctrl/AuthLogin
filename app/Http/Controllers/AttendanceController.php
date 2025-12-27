<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function create()
    {
        return view('dosen.create_session');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => ['required', 'string'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
        ]);

        $token = Str::uuid()->toString();

        $session = AttendanceSession::create([
            'course_id' => $data['course_id'],
            'instructor_id' => Auth::id(),
            'token' => $token,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'metadata' => null,
        ]);

        return redirect()->route('attendance.show', $session->id);
    }

    public function show(AttendanceSession $session)
    {
        // QR payload includes token, course_id, timestamp
        $payload = json_encode([
            'session_token' => $session->token,
            'course_id' => $session->course_id,
            'starts_at' => optional($session->starts_at)->toDateTimeString(),
        ]);

        return view('dosen.show_session', compact('session', 'payload'));
    }
}
