<?php

namespace Tests\Feature;

use App\Http\Middleware\ApiCurrencyByIp;
use App\Http\Resources\VideoSummaryResource;
use App\Video;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiCurrencyContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_nigerian_requests_use_stored_naira_prices()
    {
        $request = $this->currencyRequest('NG');
        $video = $this->video();
        $data = (new VideoSummaryResource($video))->toArray($request);

        $this->assertSame('NGN', $data['iso_code']);
        $this->assertSame('₦', $data['currency']);
        $this->assertSame(5000.0, $data['buy_price']);
        $this->assertSame(1500.0, $data['rent_price']);
    }

    public function test_non_nigerian_requests_currently_default_to_naira_prices()
    {
        $request = $this->currencyRequest('US');
        $video = $this->video();
        $data = (new VideoSummaryResource($video))->toArray($request);

        $this->assertSame('NGN', $data['iso_code']);
        $this->assertSame('₦', $data['currency']);
        $this->assertSame(5000.0, $data['buy_price']);
        $this->assertSame(1500.0, $data['rent_price']);
    }

    public function test_public_video_summary_does_not_expose_paid_stream_url()
    {
        $request = $this->currencyRequest('NG');
        $video = $this->video();
        $video->forceFill(['link' => 'https://media.example.com/private/master.m3u8']);
        $data = (new VideoSummaryResource($video))->toArray($request);

        $this->assertArrayNotHasKey('link', $data);
        $this->assertArrayNotHasKey('stream_url', $data);
    }

    private function currencyRequest($country)
    {
        $request = Request::create('/api/browse', 'GET');
        $request->headers->set('CF-IPCountry', $country);

        (new ApiCurrencyByIp())->handle($request, function ($request) {
            return $request;
        });

        return $request;
    }

    private function video()
    {
        $video = new Video();
        $video->forceFill([
            'id' => 1,
            'title' => 'Price Test',
            'allow_buy' => true,
            'allow_rent' => true,
            'buy_price' => 5000,
            'rent_price' => 1500,
            'buy_price_usd' => 12.99,
            'rent_price_usd' => 3.99,
            'content_type' => 'movie',
            'is_free' => false,
        ]);

        return $video;
    }
}
