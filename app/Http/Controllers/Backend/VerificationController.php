<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Verification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpEmail;

class VerificationController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        if (! $user) return response()->json(['success' => false, 'message' => 'User not found'], 404);

        $otp = rand(100000, 999999);
        $verify = Verification::create([
            'user_id' => $user->id,
            'unique_id' => uniqid(),
            'otp' => md5($otp),
            'type' => $request->input('type', 'register'),
            'send_via' => 'email'
        ]);

        Mail::to($user->email)->queue(new OtpEmail($otp));

        return response()->json(['success' => true, 'data' => ['unique_id' => $verify->unique_id]]);
    }

    public function update(Request $request, $unique_id)
    {
        $verify = Verification::where('user_id', Auth::id())->where('unique_id', $unique_id)->where('status', 'active')->first();
        if (! $verify) return response()->json(['success' => false, 'message' => 'Not found'], 404);

        if (md5($request->input('otp')) !== $verify->otp) {
            $verify->update(['status' => 'invalid']);
            return response()->json(['success' => false, 'message' => 'OTP invalid'], 422);
        }

        $verify->update(['status' => 'valid']);
        User::where('id', $verify->user_id)->update(['status' => 'active']);

        return response()->json(['success' => true]);
    }
}
