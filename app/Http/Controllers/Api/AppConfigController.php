<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class AppConfigController extends Controller
{
    public function show()
    {
        return response()->json([
            'data' => [
                'flutterwave_public_key' => (string) config('services.flutterwave.public_key'),
            ],
        ]);
    }
}
