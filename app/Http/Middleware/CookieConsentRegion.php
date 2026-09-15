<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Stevebauman\Location\Facades\Location;

class CookieConsentRegion
{
    /**
     * Resolve the visitor's country for cookie-consent purposes.
     *
     * Nollyflix only suppresses the banner when Nigeria (NG) is positively
     * identified. If the geo lookup fails, the banner is shown. This is the
     * safer behaviour for visitors who may be in a jurisdiction that requires
     * prior consent for non-essential cookies.
     */
    public function handle($request, Closure $next)
    {
        $countryCode = $this->countryCode($request);
        $requiresConsent = $countryCode !== 'NG';

        $request->attributes->set('cookie_country_code', $countryCode);
        $request->attributes->set('cookie_consent_required', $requiresConsent);

        View::share('cookie_country_code', $countryCode);
        View::share('cookie_consent_required', $requiresConsent);

        $response = $next($request);

        if (! $requiresConsent || $request->is('admin*')) {
            return $response;
        }

        if (! method_exists($response, 'getContent') || ! method_exists($response, 'setContent')) {
            return $response;
        }

        $contentType = (string) $response->headers->get('Content-Type');
        if ($contentType && stripos($contentType, 'text/html') === false) {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || stripos($content, '</head>') === false) {
            return $response;
        }

        // Keep this separate from the app bundle so consent UI changes do not
        // require rebuilding the frontend assets.
        $policyUrl = '/cookie-policy';
        $assets = "\n".
            '<meta name="nollyflix-cookie-consent-required" content="1">'."\n".
            '<meta name="nollyflix-cookie-policy-url" content="'.$policyUrl.'">'."\n".
            '<link rel="stylesheet" href="/css/cookie-consent.css?v=20260915">'."\n".
            '<script defer src="/js/cookie-consent.js?v=20260915"></script>'."\n";

        $content = preg_replace('/<\/head>/i', $assets.'</head>', $content, 1);
        $response->setContent($content);
        $response->headers->remove('Content-Length');

        return $response;
    }

    private function countryCode($request)
    {
        // Cloudflare is the fastest and most reliable source when available.
        $cloudflare = strtoupper(trim((string) $request->header('CF-IPCountry')));
        if ($this->validCountryCode($cloudflare)) {
            return $cloudflare;
        }

        $ip = (string) $request->ip();
        $cachedIp = (string) $request->session()->get('cookie_region_ip');
        $cachedCountry = strtoupper((string) $request->session()->get('cookie_region_country'));

        if ($ip !== '' && $cachedIp === $ip && $this->validCountryCode($cachedCountry)) {
            return $cachedCountry;
        }

        try {
            $position = Location::get($ip);
            $countryCode = strtoupper((string) optional($position)->countryCode);

            if ($this->validCountryCode($countryCode)) {
                $request->session()->put('cookie_region_ip', $ip);
                $request->session()->put('cookie_region_country', $countryCode);

                return $countryCode;
            }
        } catch (\Throwable $exception) {
            // Unknown location intentionally falls through. Unknown visitors
            // receive the consent banner rather than being treated as Nigeria.
        }

        return null;
    }

    private function validCountryCode($countryCode)
    {
        return (bool) preg_match('/^[A-Z]{2}$/', (string) $countryCode)
            && ! in_array($countryCode, ['XX', 'T1'], true);
    }

}
