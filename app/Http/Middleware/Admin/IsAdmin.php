<?php

namespace App\Http\Middleware\Admin;

use App\Services\Notify\Facades\Notify;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next, $role = null)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            if (!empty($role)) {
                $role = explode('.', $role);
                foreach ($role as $r) {
                    if ($r == auth()->user()->roled) {
                        return $next($request);
                    }
                }
                if ($request->ajax()) {
                    $result = 'false';

                    return response($result);
                }
                Notify::error('У Вас нет доступа к этой функции.');

                return redirect()->route('admin.profile.main');
            }
            app()->setLocale('ru');

            return $next($request);
        }

        return redirect()->route('admin.login');
    }
}
