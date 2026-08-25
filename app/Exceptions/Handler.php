<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);

        if (!$this->shouldEmailApiException($exception)) {
            return;
        }

        $request = request();
        $requestId = $request->attributes->get('request_id') ?: (string) Str::uuid();
        $recipient = config('api_errors.email');
        $fingerprint = sha1(get_class($exception).'|'.$exception->getFile().'|'.$exception->getLine());
        $cacheKey = 'api-error-email:'.$fingerprint;
        $cooldown = max(1, (int) config('api_errors.cooldown_minutes', 10));

        if (!Cache::add($cacheKey, true, now()->addMinutes($cooldown))) {
            return;
        }

        try {
            $body = implode("\n", [
                'A Nollyflix mobile API request failed.',
                '',
                'Request ID: '.$requestId,
                'Environment: '.app()->environment(),
                'Method: '.$request->method(),
                'URL: '.$request->fullUrl(),
                'User ID: '.optional($request->user())->id,
                'Exception: '.get_class($exception),
                'Message: '.$exception->getMessage(),
                'Location: '.$exception->getFile().':'.$exception->getLine(),
            ]);

            Mail::raw($body, function ($message) use ($recipient, $requestId) {
                $message->to($recipient)->subject('[Nollyflix API Error] '.$requestId);
            });
        } catch (Throwable $mailException) {
            Log::channel('mobile_api')->error('API error email could not be sent', [
                'request_id' => $requestId,
                'error' => $mailException->getMessage(),
            ]);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {    

        if ($request->is('api/*')) {
            $requestId = $request->attributes->get('request_id') ?: (string) Str::uuid();

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => 'Please correct the highlighted fields.',
                    'errors' => $exception->errors(),
                    'request_id' => $requestId,
                ], 422)->header('X-Request-ID', $requestId);
            }

            if ($this->shouldReport($exception)) {
                return response()->json([
                    'message' => 'The request could not be completed. Please contact support with this request ID.',
                    'request_id' => $requestId,
                ], 500)->header('X-Request-ID', $requestId);
            }
        }

        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->route('login');
        }
        return parent::render($request, $exception);
    }

    private function shouldEmailApiException(Throwable $exception)
    {
        return app()->bound('request')
            && request()->is('api/*')
            && request()->isMethod('post')
            && (bool) config('api_errors.email')
            && $this->shouldReport($exception);
    }
}
