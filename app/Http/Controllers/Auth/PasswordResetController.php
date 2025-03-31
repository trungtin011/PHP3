<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }

        $user->password_reset_token = Str::random(64);
        $user->password_reset_token_created_at = now();
        $user->save();

        // Send email with reset link
        $resetLink = route('password.reset', ['token' => $user->password_reset_token]);
        Mail::send('emails.password-reset', ['resetLink' => $resetLink], function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Đặt lại mật khẩu của bạn');
        });

        return back()->with('status', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)
            ->where('password_reset_token', $request->token)
            ->first();

        if (!$user || Carbon::parse($user->password_reset_token_created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        $user->password = Hash::make($request->password);
        $user->password_reset_token = null;
        $user->password_reset_token_created_at = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Mật khẩu của bạn đã được đặt lại thành công.');
    }
}
