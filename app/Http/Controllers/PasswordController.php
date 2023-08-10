<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
    public function updatePassword(Request $request)
    {
        $validate = $request->validate([
            'currentPassword' => 'required|password',
            'password' => 'required|confirmed'
        ]);

        $user = User::find(auth()->user()->id);
        $user->password = Hash::make($validate['password']);
        $user->save();

        return redirect('/profile');
    }

    public function viewForgotPassword(Request $request)
    {
        return view('password.forgot');
    }

    public function viewResetPassword(Request $request, string $token)
    {
        return view('password.reset', ['token' => $token]);
    }

    public function forgotPassword(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT ? back()->with(['status' => __($status)]) : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(Request $request)
    {
        $validate = $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset($validate, function(User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();
        });

        return $status === Password::PASSWORD_RESET ? redirect()->route('login')->with('status', __($status)) : back()->withErrors(['email' => [__($status)]]);
    }
}
