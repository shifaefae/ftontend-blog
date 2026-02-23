<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika sudah login (ada api_token di session), redirect ke dashboard
        if (session()->has('api_token')) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
