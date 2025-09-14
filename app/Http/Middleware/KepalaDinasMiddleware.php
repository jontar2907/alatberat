<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class KepalaDinasMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'kepala_dinas') {
            return $next($request);
        }

        abort(403, 'Unauthorized. Only Kepala Dinas can access this page.');
    }
}
