<?php

namespace Tests\Feature;

use App\Http\Middleware\ApiCurrencyByIp;
use App\Http\Middleware\CurrencyByIp;
use App\Services\RequestCurrencyResolver;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Stevebauman\Location\Facades\Location;
use Tests\TestCase;

class RegionExclusionsTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        config(['location.fallbacks' => [], 'location.testing.enabled' => false]);
        Location::clearResolvedInstance('location');
    }

    /** @dataProvider confirmedCountries */
    public function test_africa_exclusions_only_block_confirmed_african_visitors($country, $blocked)
    {
        Http::fake();
        foreach ([CurrencyByIp::class, ApiCurrencyByIp::class] as $middleware) {
            $request = $this->requestWithSession();
            $request->headers->set('CF-IPCountry', $country);
            // An earlier currency preference or session must not set geography.
            $request->session()->put('currency_manual', 'NGN');
            $request->session()->put('userLocation', json_encode(['countryCode' => 'NG']));
            (new $middleware(new RequestCurrencyResolver()))->handle($request, function () { return response('OK'); });
            $this->app->instance('request', $request);
            $video = new Video;
            $video->blocked_continents = ['AF'];
            $this->assertSame($blocked, $video->isBlockedInCurrentRegion());
            $this->assertSame($country, $request->attributes->get('country_code'));
        }
        Http::assertNothingSent();
    }

    public function confirmedCountries()
    {
        return [['DE', false], ['FR', false], ['US', false], ['NG', true], ['GH', true], ['ZA', true]];
    }

    public function test_lookup_failure_keeps_naira_pricing_without_inventing_an_african_location()
    {
        Http::fake(['*' => Http::response([], 503)]);
        foreach ([CurrencyByIp::class, ApiCurrencyByIp::class] as $middleware) {
            $request = $this->requestWithSession();
            $request->attributes->set('continent_code', 'AF');
            (new $middleware(new RequestCurrencyResolver()))->handle($request, function () { return response('OK'); });
            $this->app->instance('request', $request);
            $video = new Video;
            $video->blocked_continents = ['AF'];
            $this->assertNull($request->attributes->get('country_code'));
            $this->assertNull(Video::detectContinentCode());
            $this->assertFalse($video->isBlockedInCurrentRegion());
            $this->assertSame('NGN', $request->attributes->get('currency_code'));
        }
        // Exactly one lookup per request, not another from each video accessor.
        Http::assertSentCount(2);
    }

    public function test_german_ip_lookup_without_cloudflare_stays_outside_africa()
    {
        Http::fake(['*' => Http::response(['country' => 'DE'], 200)]);
        $request = $this->requestWithSession();
        (new CurrencyByIp(new RequestCurrencyResolver()))->handle($request, function () { return response('OK'); });
        $this->app->instance('request', $request);
        $video = new Video;
        $video->blocked_continents = ['AF'];
        $this->assertFalse($video->isBlockedInCurrentRegion());
        $this->assertSame('EU', Video::detectContinentCode());
        $this->assertSame('USD', $request->attributes->get('currency_code'));
        Http::assertSentCount(1);
    }

    public function test_routes_without_currency_middleware_honor_the_german_country_header()
    {
        Http::fake(['*' => Http::response(['country' => 'NG'], 200)]);
        $request = $this->requestWithSession('/watch/hls/video/test');
        $request->headers->set('CF-IPCountry', 'DE');
        $this->app->instance('request', $request);
        $video = new Video;
        $video->blocked_continents = ['AF'];
        $this->assertSame('EU', Video::detectContinentCode());
        $this->assertFalse($video->isBlockedInCurrentRegion());
        Http::assertNothingSent();
    }

    public function test_movie_page_does_not_show_africa_warning_to_germany_or_unknown_visitors()
    {
        factory(\App\SystemSetting::class)->create(['address' => 'Test', 'site_email' => 'support@example.test']);
        $video = new Video;
        $video->forceFill(['title' => 'Germany regression', 'slug' => 'germany-regression',
            'is_active' => true, 'blocked_continents' => ['AF'],
            'buy_price' => 1000, 'rent_price' => 500])->save();
        $_SERVER['REQUEST_URI'] = '/browse/'.$video->slug;
        $this->withHeader('CF-IPCountry', 'DE')->get('/browse/'.$video->slug)->assertOk()
            ->assertDontSee('This movie is not available in your region.');
        $this->withHeader('CF-IPCountry', 'NG')->get('/browse/'.$video->slug)->assertOk()
            ->assertSee('This movie is not available in your region.');
        Http::fake(['*' => Http::response([], 503)]);
        $this->withHeader('CF-IPCountry', 'XX')->get('/browse/'.$video->slug)->assertOk()
            ->assertDontSee('This movie is not available in your region.');
    }

    public function test_playback_api_allows_germany_and_still_enforces_africa_exclusions()
    {
        config(['services.mobile_playback.ignore_geofence' => false]);
        $video = new Video;
        $video->forceFill(['title' => 'Playback region', 'slug' => 'playback-region',
            'is_active' => true, 'is_free' => true, 'blocked_continents' => ['AF'],
            'content_type' => 'movie', 'link' => 'https://media.example.test/movie.m3u8'])->save();
        $this->withHeader('CF-IPCountry', 'DE')->getJson('/api/video/'.$video->id.'/play')->assertOk();
        $this->withHeader('CF-IPCountry', 'NG')->getJson('/api/video/'.$video->id.'/play')->assertForbidden();
        Http::fake(['*' => Http::response([], 503)]);
        $this->withHeader('CF-IPCountry', 'XX')->getJson('/api/video/'.$video->id.'/play')->assertOk();
    }

    private function requestWithSession($path = '/browse/test')
    {
        $request = Request::create($path, 'GET', [], [], [], ['REMOTE_ADDR' => '8.8.8.8']);
        $request->setLaravelSession(app('session.store'));
        return $request;
    }
}
