<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is authenticated via 'admins' guard, redirect them
        if (Auth::guard('admins')->check()) {
            return redirect()->route('admin.dashboard');
        }

        // Allow the request to proceed if the user is a guest
        return $next($request);
    }
}
