<?php

namespace App\Http\Middleware;

use Closure;

class IsCompany
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->user()->type) return redirect()->route('cabinet.profile');

        return $next($request);
    }
}
