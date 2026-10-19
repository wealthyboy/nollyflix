<?php

namespace Tests\Unit;

use App\Services\FirebasePushService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class FirebasePushServiceTest extends TestCase
{
    public function test_movie_payload_stays_dismissible_without_a_timeout_or_shared_replacement_tag()
    {
        config(['services.firebase.project_id' => 'test-project']);
        $history = [];
        $stack = HandlerStack::create(new MockHandler([new Response(200, [], '{"name":"test-message"}')]));
        $stack->push(Middleware::history($history));
        $service = new class(new Client(['handler' => $stack])) extends FirebasePushService {
            protected function accessToken() { return 'fake-test-token'; }
        };
        $service->send('fake-device-token', 'Custom title', 'Custom body', [
            'video_id' => 31, 'url' => 'nollyflix://video/31',
        ]);
        $message = json_decode((string) $history[0]['request']->getBody(), true)['message'];
        $this->assertSame(['title' => 'Custom title', 'body' => 'Custom body'], $message['notification']);
        $this->assertSame('31', $message['data']['video_id']);
        $this->assertSame('nollyflix://video/31', $message['data']['url']);
        $this->assertFalse($message['android']['notification']['sticky']);
        $this->assertArrayNotHasKey('tag', $message['android']['notification']);
        $this->assertArrayNotHasKey('timeout_after', $message['android']['notification']);
    }
}
