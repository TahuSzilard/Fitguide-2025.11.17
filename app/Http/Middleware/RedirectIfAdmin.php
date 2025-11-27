<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdmin
{
    public function handle($request, Closure $next)
    {
        // Ha admin és NEM admin URL-t nyit meg, menjen dashboardra
        if (Auth::check() && Auth::user()->role === 'admin') {
            if (! $request->is('admin/*')) {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
