<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }


    public function authenticate(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return view('Admin.index');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }




    public function logout()
    {
        Auth::guard('admin')->logout(); // logout only admin

        return redirect('/');    }

    // public function logout()
    // {
    //     Auth::logout();
    //     request()->session()->invalidate();
    //     request()->session()->regenerateToken();

    //     return redirect('/');
    // }
}
