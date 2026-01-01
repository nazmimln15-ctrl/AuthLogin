<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index()
    {
        $base = url('/api/admin/users');

        $token = session('api_token');
        if (! $token && Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('frontend')->plainTextToken;
            session(['api_token' => $token]);
        }
        

        $dosenResp = Http::withToken($token)->get($base, ['role' => 'dosen']);
        $mahasiswaResp = Http::withToken($token)->get($base, ['role' => 'mahasiswa']);

        $dosen = $dosenResp->successful() ? $dosenResp->json('data') : collect();
        $mahasiswa = $mahasiswaResp->successful() ? $mahasiswaResp->json('data') : collect();

        return view('admin.user', [
            'dosen' => $dosen,
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function create()
    {
        return view('admin.user_create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $token = session('api_token');
        $resp = Http::withToken($token)->post(url('/api/admin/users'), $data);
        if ($resp->successful()) {
            return redirect()->route('admin.users.index')->with('success', 'User created.');
        }
        return back()->with('failed', 'API error: ' . $resp->body());
    }

    public function edit(User $user)
    {
        $token = session('api_token');
        $resp = Http::withToken($token)->get(url('/api/admin/users/' . $user->id));
        $userData = $resp->successful() ? $resp->json('data') : $user->toArray();
        return view('admin.user_edit', ['user' => (object) $userData]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $token = session('api_token');
        $resp = Http::withToken($token)->put(url('/api/admin/users/' . $user->id), $data);
        if ($resp->successful()) {
            return redirect()->route('admin.users.index')->with('success', 'User updated.');
        }
        return back()->with('failed', 'API error: ' . $resp->body());
    }

    public function destroy(User $user)
    {
        $token = session('api_token');
        $resp = Http::withToken($token)->delete(url('/api/admin/users/' . $user->id));
        if ($resp->successful()) {
            return redirect()->route('admin.users.index')->with('success', 'User deleted.');
        }
        return back()->with('failed', 'API error: ' . $resp->body());
    }
}
