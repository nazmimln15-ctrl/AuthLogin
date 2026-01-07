<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Verification;
use App\Mail\OtpEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;



class VerificationController extends Controller
{

    public function index()
    {
        return view('verification.index');
    }
    // public function send_otp() {}

    public function show($unique_id)
    {
        // Keep the view rendering locally; backend handles create/verify actions.
        return view('verification.show', compact('unique_id'));
    }

    public function update(Request $request, $unique_id)
    {
        abort(404, 'Use API endpoints from the frontend to verify OTP.');
 //       
        // $verify = Verification::where('user_id', Auth::id())
        //     ->where('unique_id', $unique_id)
        //     ->where('status', 'active')
        //     ->first();

        // if (!$verify) abort(404);

        // if (md5($request->otp) !== $verify->otp) {
        //     $verify->update(['status' => 'invalid']);
        //     return redirect('/verify')->with('failed', 'OTP salah');
        // }

        // $verify->update(['status' => 'valid']);

        // User::where('id', $verify->user_id)
        //     ->update(['status' => 'active']);

        return redirect('/mahasiswa');
    }

    public function store(Request $request)
    {
        abort(404, 'Use API endpoints from the frontend to request verification.');
    }
}
