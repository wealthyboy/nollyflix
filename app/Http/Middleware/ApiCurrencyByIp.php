<?php

namespace App\Http\Middleware;

use App\Services\RequestCurrencyResolver;
use Closure;

class ApiCurrencyByIp
{
    protected $resolver;

    public function __construct(RequestCurrencyResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function handle($request, Closure $next)
    {
        $pricing = $this->resolver->resolve($request);

        $request->attributes->set('country_code', $pricing['country_code']);
        $request->attributes->set('currency_code', $pricing['currency_code']);
        $request->attributes->set('currency_symbol', $pricing['currency_symbol']);

        $continent = config('continents.country_to_continent.'.$pricing['country_code']);
        if ($continent) {
            $request->attributes->set('continent_code', $continent);
        }

        return $next($request);
    }
}
