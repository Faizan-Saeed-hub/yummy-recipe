<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('customer')) {
            return redirect('/customer/login')
                ->with('error', 'Please login or register to proceed');
        }

        return $next($request);
    }
}
