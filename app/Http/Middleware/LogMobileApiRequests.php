<?php

namespace App\Http\Middleware;

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
        if (!$request->isMethod('post')) {
            return $next($request);
        }

        $requestId = $request->headers->get('X-Request-ID') ?: (string) Str::uuid();
        $request->attributes->set('request_id', $requestId);
        $startedAt = microtime(true);

        $context = [
            'request_id' => $requestId,
            'method' => $request->method(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_id' => optional($request->user())->id,
            'payload' => $this->redact($request->all()),
        ];

        try {
            $response = $next($request);
            $context['status'] = $response->getStatusCode();
            $context['duration_ms'] = round((microtime(true) - $startedAt) * 1000, 2);

            Log::channel('mobile_api')->log(
                $response->getStatusCode() >= 400 ? 'warning' : 'info',
                'Mobile API POST request',
                $context
            );
            $response->headers->set('X-Request-ID', $requestId);

            return $response;
        } catch (Throwable $exception) {
            $context['duration_ms'] = round((microtime(true) - $startedAt) * 1000, 2);
            $context['exception'] = get_class($exception);
            $context['error'] = $exception->getMessage();
            Log::channel('mobile_api')->error('Mobile API POST request failed', $context);
            throw $exception;
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
