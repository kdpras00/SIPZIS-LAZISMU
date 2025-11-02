<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // If user is admin, redirect them to admin dashboard
            if ($user->role === 'admin') {
                // Only redirect if they're trying to access public pages
                if ($request->is('/') || $request->is('program*') || $request->is('berita*') || $request->is('artikel*') || $request->is('tentang*')) {
                    return redirect()->route('dashboard');
                }
            }
            // If user is muzakki, ensure they're not accessing admin routes
            elseif ($user->role === 'muzakki') {
                // If they're trying to access admin routes, redirect to home
                if ($request->is('admin*') || $request->is('dashboard') && !$request->is('muzakki/dashboard*')) {
                    // Check if it's actually the admin dashboard route
                    if (str_contains($request->path(), 'dashboard') && !str_contains($request->path(), 'muzakki')) {
                        return redirect()->route('muzakki.dashboard');
                    }
                }
            }
        }

        return $next($request);
    }
}
