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
        abort(404, 'Use API endpoints from the frontend to create sessions.');
    }

    public function show(AttendanceSession $session)
    {
        // The view will fetch session details via axios on the client.
        return view('dosen.show_session');
    }
}
