<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class CustomerAuthController extends Controller
{

    public function register()
    {
        return view('customer.auth.signup');
    }




    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
            'age' => 'required|integer|min:10|max:100',
            'dietary_restrictions' => 'nullable|array',
            'dietary_restrictions.*' => 'string',
            'health_conditions' => 'nullable|array',
            'health_conditions.*' => 'string',
            'use_profile_suggestion' => 'nullable|boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'age' => $request->age,
            'dietary_restrictions' => $request->input('dietary_restrictions', []),
            'health_conditions' => $request->input('health_conditions', []),
            'use_profile_suggestion' => $request->has('use_profile_suggestion'),
        ]);

        return redirect('/customer/login')->with('success', 'Account created successfully. Please login.');
    }



    public function index()
    {
        return view('customer.auth.login');
    }




    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // this is for checking
        $customer = User::where('email', $request->email)->first();


        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            session()->put('customer', $customer);
            return redirect()->intended('/')->with('success', 'Loged In');
        }

        return redirect('/customer/registration')->with('error', 'Invalid credentials. Please try again.');
    }




    public function logout()
    {
        session()->forget('customer');
        return redirect('/')
            ->with('success', 'Logged out successfully');
    }

}


