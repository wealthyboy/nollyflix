<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use RuntimeException;
use Throwable;

class FirebasePushService
{
    protected $client;
    protected $credentials;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function send($token, $title, $body, array $data = [])
    {
        $projectId = config('services.firebase.project_id');

        if (! $projectId) {
            throw new RuntimeException('FIREBASE_PROJECT_ID is not configured.');
        }

        $stringData = [];
        foreach ($data as $key => $value) {
            $stringData[(string) $key] = is_scalar($value) || $value === null
                ? (string) $value
                : json_encode($value);
        }

        $response = $this->client->post(
            'https://fcm.googleapis.com/v1/projects/'.rawurlencode($projectId).'/messages:send',
            [
                'http_errors' => false,
                'headers' => [
                    'Authorization' => 'Bearer '.$this->accessToken(),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'message' => [
                        'token' => $token,
                        'notification' => ['title' => $title, 'body' => $body],
                        'data' => $stringData,
                        'android' => [
                            'priority' => 'high',
                            'notification' => ['sound' => 'default'],
                        ],
                        'apns' => [
                            'payload' => [
                                'aps' => ['sound' => 'default', 'badge' => 1],
                            ],
                        ],
                    ],
                ],
                'timeout' => 20,
            ]
        );

        $payload = json_decode((string) $response->getBody(), true);
        if ($response->getStatusCode() >= 300) {
            $message = data_get($payload, 'error.message', 'Firebase rejected the push notification.');
            $status = data_get($payload, 'error.status');
            throw new RuntimeException(trim($status.' '.$message));
        }

        return $payload;
    }

    public function isInvalidTokenError(Throwable $exception)
    {
        $message = strtoupper($exception->getMessage());

        return strpos($message, 'UNREGISTERED') !== false
            || strpos($message, 'REGISTRATION-TOKEN-NOT-REGISTERED') !== false
            || strpos($message, 'REQUESTED ENTITY WAS NOT FOUND') !== false;
    }

    protected function accessToken()
    {
        $credentials = $this->credentials();
        $cacheKey = 'firebase-access-token:'.sha1($credentials['client_email']);

        return Cache::remember($cacheKey, now()->addMinutes(50), function () use ($credentials) {
            $now = time();
            $header = $this->base64Url(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
            $claims = $this->base64Url(json_encode([
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                'aud' => $credentials['token_uri'] ?: 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ]));
            $unsigned = $header.'.'.$claims;

            if (! openssl_sign($unsigned, $signature, $credentials['private_key'], OPENSSL_ALGO_SHA256)) {
                throw new RuntimeException('Unable to sign the Firebase service-account request.');
            }

            $assertion = $unsigned.'.'.$this->base64Url($signature);
            $response = $this->client->post($credentials['token_uri'] ?: 'https://oauth2.googleapis.com/token', [
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $assertion,
                ],
                'timeout' => 20,
            ]);
            $payload = json_decode((string) $response->getBody(), true);

            if (empty($payload['access_token'])) {
                throw new RuntimeException('Firebase did not return an OAuth access token.');
            }

            return $payload['access_token'];
        });
    }

    protected function credentials()
    {
        if ($this->credentials) {
            return $this->credentials;
        }

        $configured = config('services.firebase.credentials');
        if (! $configured) {
            throw new RuntimeException('FIREBASE_CREDENTIALS is not configured.');
        }

        $json = ltrim($configured);
        if (strpos($json, '{') !== 0) {
            $path = $configured;
            if (strpos($path, DIRECTORY_SEPARATOR) !== 0) {
                $path = base_path($path);
            }
            if (! is_readable($path)) {
                throw new RuntimeException('The Firebase credentials file cannot be read.');
            }
            $json = file_get_contents($path);
        }

        $credentials = json_decode($json, true);
        if (empty($credentials['client_email']) || empty($credentials['private_key'])) {
            throw new RuntimeException('The Firebase service-account credentials are invalid.');
        }

        $credentials['token_uri'] = isset($credentials['token_uri']) ? $credentials['token_uri'] : null;
        return $this->credentials = $credentials;
    }

    protected function base64Url($value)
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}
