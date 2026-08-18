<?php

namespace App\Http\Middleware;

use Closure;

class ApiCurrencyByIp
{
    public function handle($request, Closure $next)
    {
        // Mobile pricing is temporarily NGN-only. Keep this decision in one
        // middleware so IP-based NGN/USD pricing can be restored later without
        // changing catalogue, checkout, or playback contracts.
        $request->attributes->set('country_code', 'NG');
        $request->attributes->set('currency_code', 'NGN');
        $request->attributes->set('currency_symbol', '₦');

        return $next($request);
    }
}
