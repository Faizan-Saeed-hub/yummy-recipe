<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function users()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }
    }
