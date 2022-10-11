<?php

namespace App\Http\Middleware;

use App\Services\ExchangeRateDetector\ExchangeRateDetector;
use App\Services\LanguageManager\Facades\LanguageManager as Manager;
use Closure;
use Illuminate\Http\Request;

class LanguageManager
{
    /**
     * @var \App\Services\ExchangeRateDetector\ExchangeRateDetector
     */
    protected $exchangeRateDetector;

    public function __construct(ExchangeRateDetector $exchangeRateDetector)
    {
        $this->exchangeRateDetector = $exchangeRateDetector;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->exchangeRateDetector->detectCountry($request->ip());

        $result = Manager::middleware();

        return $result ? $result : $next($request);
    }
}
