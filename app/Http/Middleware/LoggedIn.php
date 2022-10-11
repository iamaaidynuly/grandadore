<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class LoggedIn
{

    public function handle($request, Closure $next, $type = null)
    {
        if (authUser()) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
