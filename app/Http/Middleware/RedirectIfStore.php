<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfStore
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::store()) {
            return redirect('/store/dashboard');
        }

        return $next($request);
    }
}
