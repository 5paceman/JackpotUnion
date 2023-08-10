<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{   
    public function viewLogin(Request $request)
    {
        return view('login');
    }

    public function viewRegister(Request $request)
    {
        if($request->has('invite'))
        {
            session()->flash('invite', $request->get('invite'));
        }
        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'first_name' => $validate['first_name'],
            'last_name' => $validate['last_name'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password'])
        ]);

        if($user)
        {
            $user->newUserCheckInvites();
            return redirect('/login');
        }

        return back()->withErrors(['Unknown error']);
    }

    public function login(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $credentials = [
            'email' => $validate['email'],
            'password' => $validate['password']
        ];

        if(Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
     
        return redirect('/login');
    }
}
