<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:50',
            'password' => 'required|min:5|max:20',
        ]);
        if(Auth::attempt($request->only('email', 'password'), $request->remember)){
            // create a token for API usage and store in session so views/javascript can use it
            $user = Auth::user();
            $user = \App\Models\User::find(Auth::id());
            try {
                $token = $user->createToken('frontend')->plainTextToken;
                session(['api_token' => $token]);
            } catch (\Throwable $e) {
                // ignore token creation errors
            }
            if($user->role == 'mahasiswa') return redirect('/mahasiswa'); 
            return redirect('/dashboard');
        }
        return back()->with('failed', 'Login failed! Please check your email or password.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email|max:50',
            'password' => 'required|min:8|max:20',
            'confirm-password' => 'required|min:8|max:20|same:password',
        ]);
        $request['status'] = 'verify';
        $user = User::create($request->all());
        Auth::login($user);
        return redirect('/mahasiswa');
    }

    public function logout()
    {
        Auth::logout(Auth::user());
        return redirect('/login');
    }


}
