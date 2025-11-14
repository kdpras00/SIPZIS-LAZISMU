<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // Check if user has any of the required roles
        if (!empty($roles)) {
            // Normalize role comparison (case-insensitive and handle typos)
            $userRole = strtolower(trim($user->role ?? ''));
            $allowedRoles = array_map(function($role) {
                return strtolower(trim($role));
            }, $roles);
            
            // Also check for common typos
            if ($userRole === 'muzzaki' && in_array('muzakki', $allowedRoles)) {
                $userRole = 'muzakki';
            }
            
            if (!in_array($userRole, $allowedRoles)) {
                \Log::warning('Role mismatch', [
                    'user_id' => $user->id,
                    'user_role' => $user->role,
                    'required_roles' => $roles,
                    'path' => $request->path()
                ]);
                abort(403, 'Anda tidak memiliki akses ke halaman ini. Role Anda: ' . $user->role);
            }
        }

        return $next($request);
    }
}
