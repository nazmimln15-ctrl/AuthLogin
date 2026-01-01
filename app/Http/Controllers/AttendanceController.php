<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\AttendanceSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


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

        $token = session('api_token');
        if (! $token && Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('frontend')->plainTextToken;
            session(['api_token' => $token]);
        }
        $resp = Http::withToken($token)->post(url('/api/admin/sessions'), $data);
        if ($resp->successful()) {
            $session = $resp->json('data');
            return redirect()->route('attendance.show', $session['id']);
        }
        return back()->with('failed', 'API error: ' . $resp->body());
    }

    public function show(AttendanceSession $session)
    {
        $token = session('api_token');
        $resp = Http::withToken($token)->get(url('/api/admin/sessions/' . $session->id));
        if ($resp->successful()) {
            $sessionData = $resp->json('data');
            $payload = json_encode([
                'session_token' => $sessionData['token'] ?? null,
                'course_id' => $sessionData['course_id'] ?? null,
                'starts_at' => $sessionData['starts_at'] ?? null,
            ]);
            return view('dosen.show_session', ['session' => (object) $sessionData, 'payload' => $payload]);
        }
        abort(404);
    }
}
