<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Ambil config
        $cspConfig = config('csp.policy');

        // Build CSP string
        $directives = [];
        foreach ($cspConfig as $directive => $sources) {
            $directives[] = $directive . ' ' . implode(' ', $sources);
        }

        $cspHeader = implode('; ', $directives);

        // Tentukan nilai COOP khusus halaman login (butuh akses pop-up)
        $coopValue = 'unsafe-none';
        if ($request->is('login') || $request->is('login/*')) {
            $coopValue = 'same-origin-allow-popups';
        }

        // Tambahkan header CSP
        $response->headers->set('Content-Security-Policy', $cspHeader);
        $response->headers->set('Cross-Origin-Opener-Policy', $coopValue);
        $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');

        return $response;
    }
}
