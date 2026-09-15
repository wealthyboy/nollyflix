<?php

namespace App\Http\Middleware;

use App\Services\RequestCurrencyResolver;
use Closure;

class CurrencyByIp
{
    protected $resolver;

    public function __construct(RequestCurrencyResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function handle($request, Closure $next)
    {
        $pricing = $this->resolver->resolve($request);

        // A visitor can manually override the IP-selected currency from the
        // movie show page. Keep that choice in the session until they switch
        // again. Prices remain the manually-entered NGN/USD values; no FX
        // conversion is performed.
        $manualCurrency = strtoupper((string) $request->session()->get('currency_manual'));

        if (in_array($manualCurrency, ['NGN', 'USD'], true)) {
            $pricing['currency_code'] = $manualCurrency;
            $pricing['currency_symbol'] = $manualCurrency === 'USD' ? '$' : '₦';
        }

        $request->attributes->set('country_code', $pricing['country_code']);
        $request->attributes->set('currency_code', $pricing['currency_code']);
        $request->attributes->set('currency_symbol', $pricing['currency_symbol']);

        $continent = config('continents.country_to_continent.'.$pricing['country_code']);
        if ($continent) {
            $request->attributes->set('continent_code', $continent);
        }

        // Keep the legacy accessors working, but rate is always 1 because
        // Nollyflix uses manually-entered NGN and USD prices, not live FX.
        $rate = [
            'rate' => 1,
            'country' => $pricing['country_name'],
            'code' => $pricing['currency_code'],
            'symbol' => $pricing['currency_symbol'],
        ];

        $request->session()->put('country_name', $pricing['country_name']);
        $request->session()->put('switch', $pricing['currency_code']);
        $request->session()->put('rate', json_encode(collect($rate)));
        $request->session()->put('userLocation', json_encode([
            'ip' => $request->ip(),
            'countryCode' => $pricing['country_code'],
            'countryName' => $pricing['country_name'],
        ]));

        return $next($request);
    }
}
