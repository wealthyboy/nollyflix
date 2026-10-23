<?php

namespace App\Http\Middleware;

use App\MobileApiLog;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class LogMobileApiRequests
{
    private const SENSITIVE_KEYS = [
        'password', 'password_confirmation', 'new_password',
        'new_password_confirmation', 'token', 'api_token', 'access_token',
        'authorization', 'secret', 'private_key', 'public_key', 'card',
        'card_number', 'cvv', 'pin',
    ];

    public function handle($request, Closure $next)
    {
        $requestId = $request->headers->get('X-Request-ID') ?: (string) Str::uuid();
        $request->attributes->set('request_id', $requestId);
        $startedAt = microtime(true);

        $context = [
            'request_id' => $requestId,
            'method' => $request->method(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_id' => null,
            'platform' => $this->deviceHeader($request, 'X-App-Platform', 30),
            'app_version' => $this->deviceHeader($request, 'X-App-Version', 40),
            'app_build' => $this->deviceHeader($request, 'X-App-Build', 40),
            'device_brand' => $this->deviceHeader($request, 'X-Device-Brand', 80),
            'device_model' => $this->deviceHeader($request, 'X-Device-Model', 120),
            'os_version' => $this->deviceHeader($request, 'X-OS-Version', 40),
            'user_agent' => $request->userAgent(),
            'request_payload' => $this->redact(array_merge($request->query(), $request->all())),
        ];

        try {
            $response = $next($request);
            $context['status'] = $response->getStatusCode();
            $context['duration_ms'] = round((microtime(true) - $startedAt) * 1000, 2);
            $context['user_id'] = $this->resolveUserId($request);
            $context['response_payload'] = $this->responsePayload($response);
            $this->persist($context);

            Log::channel('mobile_api')->log(
                $response->getStatusCode() >= 400 ? 'warning' : 'info',
                'Mobile API request',
                $context
            );
            $response->headers->set('X-Request-ID', $requestId);

            return $response;
        } catch (Throwable $exception) {
            $context['duration_ms'] = round((microtime(true) - $startedAt) * 1000, 2);
            $context['status'] = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
            $context['user_id'] = $this->resolveUserId($request);
            $context['exception'] = get_class($exception);
            $context['error'] = $exception->getMessage();
            $this->persist($context);
            Log::channel('mobile_api')->error('Mobile API request failed', $context);
            throw $exception;
        }
    }

    private function deviceHeader($request, $name, $length)
    {
        // Diagnostic, app-reported metadata only; never use it to authorize a
        // request. Keep untrusted header values bounded and log-safe.
        $value = preg_replace('/[\x00-\x1F\x7F]/', ' ', (string) $request->header($name, ''));
        $value = trim(mb_substr($value, 0, $length));

        return $value !== '' && strcasecmp($value, 'unknown') !== 0 ? $value : null;
    }

    private function responsePayload($response)
    {
        if (!method_exists($response, 'getContent')) return null;

        $content = (string) $response->getContent();
        if ($content === '') return null;

        if (strlen($content) > 12000) {
            return [
                'truncated' => true,
                'bytes' => strlen($content),
                'preview' => mb_substr($content, 0, 12000),
            ];
        }

        $decoded = json_decode($content, true);
        if (is_array($decoded)) return $this->redact($decoded);

        return ['body' => mb_substr($content, 0, 12000)];
    }

    private function persist(array $context)
    {
        try {
            MobileApiLog::create($context);
        } catch (Throwable $exception) {
            // Monitoring must never interrupt the API request it observes.
            Log::channel('mobile_api')->warning('Mobile API database log could not be saved', [
                'request_id' => $context['request_id'] ?? null,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function resolveUserId($request)
    {
        try {
            return optional($request->user('api'))->id ?: optional($request->user())->id;
        } catch (Throwable $exception) {
            return null;
        }
    }

    private function redact(array $payload)
    {
        foreach ($payload as $key => $value) {
            if (in_array(strtolower((string) $key), self::SENSITIVE_KEYS, true)) {
                $payload[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $payload[$key] = $this->redact($value);
            }
        }

        return $payload;
    }
}
