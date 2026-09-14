<?php

namespace App\Services;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class RequestCurrencyResolver
{
    /**
     * Resolve the storefront currency from the visitor's actual country.
     *
     * Nigeria -> NGN using the manually-entered NGN video prices.
     * Everywhere else -> USD using the manually-entered USD video prices.
     * No FX conversion is performed here.
     */
    public function resolve(Request $request)
    {
        $countryCode = $this->countryCode($request);
        $countryName = null;

        if (! $countryCode) {
            try {
                $position = Location::get($request->ip());
                $countryCode = strtoupper((string) optional($position)->countryCode);
                $countryName = optional($position)->countryName;
            } catch (\Throwable $exception) {
                // If location lookup fails, fall back to NGN rather than
                // accidentally charging a Nigerian customer in USD.
            }
        }

        $countryCode = $countryCode ?: 'NG';
        $isNigeria = $countryCode === 'NG';

        return [
            'country_code' => $countryCode,
            'country_name' => $countryName ?: ($isNigeria ? 'Nigeria' : null),
            'currency_code' => $isNigeria ? 'NGN' : 'USD',
            'currency_symbol' => $isNigeria ? '₦' : '$',
        ];
    }

    private function countryCode(Request $request)
    {
        // Cloudflare supplies the visitor country without another lookup.
        $cloudflare = strtoupper(trim((string) $request->header('CF-IPCountry')));

        if (preg_match('/^[A-Z]{2}$/', $cloudflare) && ! in_array($cloudflare, ['XX', 'T1'], true)) {
            return $cloudflare;
        }

        return null;
    }
}
