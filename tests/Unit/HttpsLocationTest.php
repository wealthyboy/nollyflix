<?php

namespace Tests\Unit;

use App\Location\Drivers\HttpsIpInfo;
use App\Services\RequestCurrencyResolver;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Fluent;
use Stevebauman\Location\Drivers\MaxMind;
use Stevebauman\Location\Facades\Location;
use Tests\TestCase;

class HttpsLocationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['location.ipinfo.token' => null]);
        Location::clearResolvedInstance('location');
    }

    public function test_only_https_and_the_local_database_are_configured()
    {
        $this->assertSame(HttpsIpInfo::class, config('location.driver'));
        $this->assertSame([MaxMind::class], config('location.fallbacks'));
        $this->assertFalse(config('location.maxmind.web.enabled'));
    }

    public function test_lookup_verifies_tls_and_never_follows_redirects()
    {
        Http::fake(function ($request, $options) {
            $this->assertSame('https://ipinfo.io/8.8.8.8/json', $request->url());
            $this->assertTrue($options['verify']);
            $this->assertFalse($options['allow_redirects']);
            $this->assertSame(2, $options['timeout']);
            $this->assertSame(2, $options['connect_timeout']);

            return Http::response(['country' => 'us', 'city' => 'Mountain View'], 200);
        });
        $position = (new HttpsIpInfo())->get('8.8.8.8');
        $this->assertSame('US', $position->countryCode);
        $this->assertSame('8.8.8.8', $position->ip);
        $this->assertSame('Mountain View', $position->cityName);
        Http::assertSentCount(1);
    }

    public function test_ipv6_addresses_are_supported()
    {
        Http::fake(['*' => Http::response(['country' => 'US'], 200)]);
        $ip = '2001:4860:4860::8888';
        $this->assertSame('US', (new HttpsIpInfo())->get($ip)->countryCode);
        Http::assertSent(function ($request) use ($ip) {
            return $request->url() === 'https://ipinfo.io/'.rawurlencode($ip).'/json';
        });
    }

    public function test_optional_token_uses_a_header_not_the_url()
    {
        config(['location.ipinfo.token' => 'test-location-token']);
        Http::fake(['*' => Http::response(['country' => 'NG'], 200)]);
        $this->assertSame('NG', (new HttpsIpInfo())->get('8.8.8.8')->countryCode);
        Http::assertSent(function ($request) {
            return $request->hasHeader('Authorization', 'Bearer test-location-token')
                && $request->url() === 'https://ipinfo.io/8.8.8.8/json';
        });
    }

    public function test_public_ipv4_mapped_ipv6_uses_the_public_ipv4_address()
    {
        Http::fake(['*' => Http::response(['country' => 'US'], 200)]);
        $this->assertSame('US', (new HttpsIpInfo())->get('::ffff:8.8.8.8')->countryCode);
        Http::assertSent(function ($request) {
            return $request->url() === 'https://ipinfo.io/8.8.8.8/json';
        });
    }

    /** @dataProvider privateOrInvalidAddresses */
    public function test_private_and_invalid_addresses_are_not_sent_to_the_provider($ip)
    {
        Http::fake();
        $this->assertFalse((new HttpsIpInfo())->get($ip));
        Http::assertNothingSent();
    }

    public function privateOrInvalidAddresses()
    {
        return [['127.0.0.1'], ['10.0.0.1'], ['192.168.1.1'], ['::1'],
            ['fe80::1'], ['0.0.0.0'], ['not-an-ip'], ['8.8.8.8/../../json'],
            ['::ffff:127.0.0.1'], ['::ffff:10.0.0.1'], ['::ffff:7f00:1']];
    }

    /** @dataProvider rejectedResponses */
    public function test_bad_responses_and_redirects_fail_without_an_insecure_retry($body, $status)
    {
        Http::fake(['*' => Http::response($body, $status, ['Location' => 'http://ipinfo.io/8.8.8.8/json'])]);
        $this->assertFalse((new HttpsIpInfo())->get('8.8.8.8'));
        Http::assertSentCount(1);
        Http::assertSent(function ($request) {
            return strpos($request->url(), 'https://ipinfo.io/') === 0;
        });
    }

    public function rejectedResponses()
    {
        return [[['country' => 'US'], 302], [['error' => 'unauthorized'], 401],
            [['error' => 'rate limited'], 429], [[], 500], ['not-json', 200],
            [['error' => 'invalid IP'], 200], [['country' => ['US']], 200],
            [['country' => 'United States'], 200], [['country' => 'XX'], 200]];
    }

    public function test_tls_or_connection_failure_never_retries_over_http()
    {
        $attempts = 0;
        Http::fake(function ($request) use (&$attempts) {
            $attempts++;
            $this->assertSame('https://ipinfo.io/8.8.8.8/json', $request->url());
            throw new ConnectionException('TLS verification failed');
        });
        $this->assertFalse((new HttpsIpInfo())->get('8.8.8.8'));
        $this->assertSame(1, $attempts);
    }

    public function test_a_failed_https_request_can_use_the_local_database_driver()
    {
        Http::fake(['*' => Http::response([], 429)]);
        $local = new class extends MaxMind {
            protected function process($ip)
            {
                return new Fluent(['country' => 'Nigeria', 'country_code' => 'NG']);
            }
        };
        $driver = new HttpsIpInfo();
        $driver->fallback($local);
        $this->assertSame('NG', $driver->get('8.8.8.8')->countryCode);
        Http::assertSentCount(1);
    }

    public function test_cloudflare_country_detection_still_skips_external_lookup()
    {
        Http::fake();
        $request = Request::create('/api/browse', 'GET');
        $request->headers->set('CF-IPCountry', 'US');
        $pricing = (new RequestCurrencyResolver())->resolve($request);
        $this->assertSame('US', $pricing['country_code']);
        $this->assertSame('USD', $pricing['currency_code']);
        Http::assertNothingSent();
    }

    /** @dataProvider currencyCountries */
    public function test_missing_cloudflare_country_uses_https_for_currency_detection($country, $currency)
    {
        config(['location.fallbacks' => []]);
        Http::fake(['*' => Http::response(['country' => $country], 200)]);
        $request = Request::create('/api/browse', 'GET', [], [], [], ['REMOTE_ADDR' => '8.8.8.8']);
        $pricing = (new RequestCurrencyResolver())->resolve($request);
        $this->assertSame($country, $pricing['country_code']);
        $this->assertSame($currency, $pricing['currency_code']);
        Http::assertSentCount(1);
    }

    public function currencyCountries()
    {
        return [['NG', 'NGN'], ['GB', 'USD']];
    }

    public function test_lookup_failure_keeps_the_existing_naira_default()
    {
        config(['location.fallbacks' => []]);
        Http::fake(['*' => Http::response([], 503)]);
        $request = Request::create('/api/browse', 'GET', [], [], [], ['REMOTE_ADDR' => '8.8.8.8']);
        $pricing = (new RequestCurrencyResolver())->resolve($request);
        $this->assertSame('NG', $pricing['country_code']);
        $this->assertSame('NGN', $pricing['currency_code']);
        Http::assertSentCount(1);
    }
}
