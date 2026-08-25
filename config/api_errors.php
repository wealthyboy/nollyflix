<?php

return [
    'email' => env('API_ERROR_EMAIL'),
    'cooldown_minutes' => env('API_ERROR_EMAIL_COOLDOWN', 10),
];
