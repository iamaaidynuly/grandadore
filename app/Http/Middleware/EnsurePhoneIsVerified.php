<?php

namespace App\Http\Middleware;

use Closure;

class EnsurePhoneIsVerified
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|mixed|object
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user() || !$request->user()->hasVerifiedPhone()) {
            if (!$request->user()->phone) {
                return redirect()->route('cabinet.phoneVerification.setPhone')->setStatusCode(301);
            }

            return redirect()->route('cabinet.phoneVerification.notice')->setStatusCode(301);
        }

        return $next($request);
    }
}
