<?php

namespace App\Http\Controllers;

use App\Mail\EmailChangedMailable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function view(Request $request)
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email'
        ]);

        $existingUser = User::where('email', $validate['email'])->get()->first();

        if($existingUser)
        {
            return back()->withErrors(['error' => 'Email is already taken.']);
        }

        $user = User::find(auth()->user()->id);
        $oldEmail = $user->email;
        $user->email = $validate['email'];
        if($user->save())
        {
            Mail::to($oldEmail)->send(new EmailChangedMailable($validate['email']));
            Mail::to($validate['email'])->send(new EmailChangedMailable($validate['email']));
        }
    }

    public function deleteNotification(Request $request)
    {
        $validate = $request->validate([
            'id' => 'uuid|exists:notifications,id'
        ]);

        foreach(auth()->user()->notifications as $notification)
        {
            if($notification->id == $validate['id'])
            {
                $notification->delete();
                break;
            }
        }

        return back();
    }
}
