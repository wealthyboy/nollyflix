<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;

class CookieConsentRegion
{
    /**
     * Attach Nollyflix's cookie-consent drawer to public HTML responses.
     *
     * The middleware name is retained for compatibility with the existing
     * Kernel registration, but the notice is now shown to all public web
     * visitors so the experience is consistent and easy to test. Visitors
     * can continue with essential cookies only or explicitly allow optional
     * categories before those categories are enabled.
     */
    public function handle($request, Closure $next)
    {
        $requiresConsent = ! $request->is('admin*');

        $request->attributes->set('cookie_consent_required', $requiresConsent);
        View::share('cookie_consent_required', $requiresConsent);

        $response = $next($request);

        if (! $requiresConsent) {
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

        // Standalone assets keep consent UI updates independent of the legacy
        // Laravel Mix bundle and avoid requiring a frontend rebuild.
        $policyUrl = '/cookie-policy';
        $assets = "\n".
            '<meta name="nollyflix-cookie-consent-required" content="1">'."\n".
            '<meta name="nollyflix-cookie-policy-url" content="'.$policyUrl.'">'."\n".
            '<link rel="stylesheet" href="/css/cookie-consent.css?v=20260915-2">'."\n".
            '<script defer src="/js/cookie-consent.js?v=20260915-2"></script>'."\n";

        $content = preg_replace('/<\/head>/i', $assets.'</head>', $content, 1);
        $response->setContent($content);
        $response->headers->remove('Content-Length');

        return $response;
    }
}
