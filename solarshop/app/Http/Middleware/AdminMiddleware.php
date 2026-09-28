<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('admin')->check() || ! Auth::guard('admin')->user()->is_active) {
            return redirect()->route('admin.login')->with('error', 'Please login to access the admin panel.');
        }

        return $next($request);
    }
}