<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Stevebauman\Location\Facades\Location;

class ApiCurrencyByIp
{
    public function handle($request, Closure $next)
    {
        $countryCode = $this->countryCode($request);
        $currencyCode = $countryCode === 'NG' ? 'NGN' : 'USD';

        $request->attributes->set('country_code', $countryCode);
        $request->attributes->set('currency_code', $currencyCode);
        $request->attributes->set('currency_symbol', $currencyCode === 'NGN' ? '₦' : '$');

        return $next($request);
    }

    private function countryCode($request)
    {
        foreach (['CF-IPCountry', 'X-Country-Code'] as $header) {
            $value = strtoupper((string) $request->header($header));
            if (preg_match('/^[A-Z]{2}$/', $value) && $value !== 'XX') {
                return $value;
            }
        }

        $ip = $request->ip();
        if ($this->isLocalIp($ip)) {
            return 'NG';
        }

        return Cache::remember('api-country-code:'.$ip, now()->addDay(), function () use ($ip) {
            try {
                return strtoupper((string) optional(Location::get($ip))->countryCode) ?: 'NG';
            } catch (\Throwable $exception) {
                report($exception);
                return 'NG';
            }
        });
    }

    private function isLocalIp($ip)
    {
        return $ip === '127.0.0.1'
            || $ip === '::1'
            || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
