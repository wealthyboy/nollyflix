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

        // A pricing default is not evidence of the visitor's location.
        // Keep unknown countries unknown so Africa exclusions cannot mistake
        // a failed lookup for a confirmed Nigerian visitor.
        $countryCode = $countryCode ?: null;
        $isNigeria = $countryCode === 'NG';
        $useNaira = ! $countryCode || $isNigeria;

        return [
            'country_code' => $countryCode,
            'country_name' => $countryName ?: ($isNigeria ? 'Nigeria' : null),
            'currency_code' => $useNaira ? 'NGN' : 'USD',
            'currency_symbol' => $useNaira ? '₦' : '$',
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
