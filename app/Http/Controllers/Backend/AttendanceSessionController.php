<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AttendanceSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceSession::query();
        if ($request->has('instructor_id')) {
            $query->where('instructor_id', $request->query('instructor_id'));
        }
        $sessions = $query->orderBy('starts_at', 'desc')->get();
        return response()->json(['success' => true, 'data' => $sessions]);
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

        return response()->json(['success' => true, 'data' => $session], 201);
    }

    public function show(AttendanceSession $session)
    {
        return response()->json(['success' => true, 'data' => $session]);
    }

    public function destroy(AttendanceSession $session)
    {
        $session->delete();
        return response()->json(['success' => true]);
    }
}
