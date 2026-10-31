<?php

namespace App\Location\Drivers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Fluent;
use Stevebauman\Location\Drivers\IpInfo;

class HttpsIpInfo extends IpInfo
{
    protected function url($ip)
    {
        return 'https://ipinfo.io/'.rawurlencode($ip).'/json';
    }

    protected function process($ip)
    {
        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        // PHP's IPv6 range filter does not reject IPv4-mapped private IPs.
        $packed = inet_pton($ip);
        if (strlen($packed) === 16 && substr($packed, 0, 12) === str_repeat("\0", 10)."\xff\xff") {
            $ip = inet_ntop(substr($packed, 12));
        }

        // Do not send malformed, private or reserved addresses to a provider.
        if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return false;
        }

        try {
            $request = Http::acceptJson()->timeout(static::CURL_MAX_TIME)
                ->withoutRedirecting()->withOptions([
                    'connect_timeout' => static::CURL_CONNECT_TIMEOUT,
                    'verify' => true,
                ]);

            $token = trim((string) config('location.ipinfo.token'));
            if ($token !== '') {
                $request = $request->withToken($token);
            }

            $response = $request->get($this->url($ip));
            if (! $response->successful()) {
                return false;
            }

            $data = $response->json();
            if (! is_array($data) || ! isset($data['country']) || ! is_string($data['country'])) {
                return false;
            }

            $country = strtoupper(trim($data['country']));
            if (! preg_match('/^[A-Z]{2}$/', $country) || in_array($country, ['XX', 'T1'], true)) {
                return false;
            }

            $data['country'] = $country;

            return new Fluent($data);
        } catch (\Throwable $exception) {
            // Do not log URLs, tokens, IPs or provider bodies. TLS failures,
            // timeouts and invalid responses use only the local fallback.
            return false;
        }
    }
}
