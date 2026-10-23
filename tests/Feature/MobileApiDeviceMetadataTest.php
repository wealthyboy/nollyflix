<?php

namespace Tests\Feature;

use App\Http\Middleware\LogMobileApiRequests;
use App\MobileApiLog;
use App\Permission;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class MobileApiDeviceMetadataTest extends TestCase
{
    use RefreshDatabase;

    private $previousRequestUri;

    protected function setUp(): void
    {
        parent::setUp();
        $this->previousRequestUri = $_SERVER['REQUEST_URI'] ?? null;
    }

    protected function tearDown(): void
    {
        if ($this->previousRequestUri === null) unset($_SERVER['REQUEST_URI']);
        else $_SERVER['REQUEST_URI'] = $this->previousRequestUri;
        parent::tearDown();
    }

    private function headers()
    {
        return ['X-App-Platform' => 'android', 'X-App-Version' => '1.0', 'X-App-Build' => '1',
            'X-Device-Brand' => 'Samsung', 'X-Device-Model' => 'SM-S921B', 'X-OS-Version' => '15'];
    }

    private function request(array $headers = [])
    {
        $request = Request::create('/api/video/43/play', 'GET', ['password' => 'keep-secret']);
        foreach ($headers as $name => $value) $request->headers->set($name, $value);
        return $request;
    }

    private function log(array $changes = [])
    {
        return MobileApiLog::create(array_merge([
            'request_id' => (string) Str::uuid(), 'method' => 'GET', 'path' => 'api/video/43/play',
            'status' => 200, 'duration_ms' => 20, 'platform' => 'android', 'app_version' => '1.0',
            'app_build' => '1', 'device_brand' => 'Samsung', 'device_model' => 'SM-S921B', 'os_version' => '15',
        ], $changes));
    }

    private function admin()
    {
        $_SERVER['REQUEST_URI'] = '/admin/mobile-api';
        $user = User::create(['name' => 'Monitor', 'last_name' => 'Admin', 'email' => 'monitor@example.test',
            'password' => bcrypt('test-password'), 'type' => 'admin']);
        $permission = Permission::create(['name' => 'Admin', 'code' => '1,2,3']);
        $user->users_permission()->create(['permission_id' => $permission->id]);
        DB::table('system_settings')->insert(['site_name' => 'Nollyflix', 'address' => 'Test',
            'site_email' => 'support@example.test', 'alert_email' => 'support@example.test']);
        $this->actingAs($user);
    }

    public function test_guest_requests_store_device_details_and_keep_sensitive_values_redacted()
    {
        $response = app(LogMobileApiRequests::class)->handle($this->request($this->headers()), function () {
            return response()->json(['message' => 'ok', 'token' => 'private-token']);
        });
        $this->assertSame(200, $response->getStatusCode());
        $log = MobileApiLog::firstOrFail();
        $this->assertNull($log->user_id);
        $this->assertSame('Samsung SM-S921B', $log->device_label);
        $this->assertSame('Android 15', $log->os_label);
        $this->assertSame('1.0', $log->app_version);
        $this->assertSame('1', $log->app_build);
        $this->assertSame('[REDACTED]', $log->request_payload['password']);
        $this->assertSame('[REDACTED]', $log->response_payload['token']);
    }

    public function test_failed_requests_also_keep_device_metadata()
    {
        try {
            app(LogMobileApiRequests::class)->handle($this->request($this->headers()), function () {
                throw new RuntimeException('Playback test failure');
            });
            $this->fail('The application exception must still propagate.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Playback test failure', $exception->getMessage());
        }
        $log = MobileApiLog::firstOrFail();
        $this->assertSame(500, (int) $log->status);
        $this->assertSame('SM-S921B', $log->device_model);
    }

    public function test_older_clients_still_work_and_unknown_models_are_not_guessed()
    {
        app(LogMobileApiRequests::class)->handle($this->request(), function () { return response()->json(['ok' => true]); });
        $log = MobileApiLog::firstOrFail();
        $this->assertNull($log->device_brand);
        $this->assertNull($log->device_model);
        $this->assertSame('Unknown', $log->device_label);
        $this->assertSame('Unknown', $log->os_label);
    }

    public function test_untrusted_headers_are_bounded_and_control_characters_are_removed()
    {
        $headers = $this->headers();
        $headers['X-Device-Brand'] = str_repeat('B', 200);
        $headers['X-Device-Model'] = "Model\tName";
        $headers['X-OS-Version'] = 'unknown';
        app(LogMobileApiRequests::class)->handle($this->request($headers), function () { return response()->json(['ok' => true]); });
        $log = MobileApiLog::firstOrFail();
        $this->assertSame(80, mb_strlen($log->device_brand));
        $this->assertSame('Model Name', $log->device_model);
        $this->assertNull($log->os_version);
    }

    public function test_monitor_displays_device_and_native_app_versions_and_filters_by_brand_or_model()
    {
        $this->admin();
        $samsung = $this->log();
        $apple = $this->log(['device_brand' => 'Apple', 'device_model' => 'iPhone 15 Pro (iPhone16,1)', 'platform' => 'ios', 'os_version' => '18.0']);
        $this->get(route('admin.mobile-api.index'))->assertOk()->assertSee('Samsung SM-S921B')->assertSee('Android 15');
        $this->get(route('admin.mobile-api.show', $apple->id))->assertOk()->assertSee('iPhone 15 Pro (iPhone16,1)')->assertSee('iOS 18.0');
        $this->get(route('admin.mobile-api.index', ['device' => 'Samsung']))->assertOk()
            ->assertSee('SM-S921B')->assertDontSee('iPhone 15 Pro');
        $this->get(route('admin.mobile-api.index', ['device' => 'iPhone16']))->assertOk()
            ->assertSee('iPhone 15 Pro')->assertDontSee('SM-S921B');
        $this->get(route('admin.mobile-api.show', $samsung->id))->assertOk()->assertSee('build 1');
    }

    public function test_monitor_escapes_client_supplied_device_values()
    {
        $this->admin();
        $log = $this->log(['device_model' => '<script>deviceSentinel()</script>']);
        $this->get(route('admin.mobile-api.show', $log->id))->assertOk()
            ->assertSee('&lt;script&gt;deviceSentinel()&lt;/script&gt;', false)
            ->assertDontSee('<script>deviceSentinel()</script>', false);
    }

    public function test_monitor_remains_admin_only()
    {
        // Inspect the guard's actual exception rather than invoking the legacy
        // public 404 layout, which requires unrelated website content fixtures.
        $this->withoutExceptionHandling();
        $log = $this->log();
        foreach ([route('admin.mobile-api.index'), route('admin.mobile-api.show', $log->id)] as $url) {
            try {
                $this->get($url);
                $this->fail('Guest access to the monitor must be denied.');
            } catch (HttpException $exception) {
                $this->assertSame(404, $exception->getStatusCode());
            }
        }
    }
}
