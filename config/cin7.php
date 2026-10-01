<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Cin7 Core API client
|--------------------------------------------------------------------------
|
| Credentials and tuning for the Cin7 connector. The credentials and rate
| limit are read when the connector is first resolved, and the retry values
| each time a request is constructed. Publish it with:
|
|     php artisan vendor:publish --tag=cin7-config
|
| See docs/configuration.md.
|
*/

return [
    // Sent as the api-auth-accountid and api-auth-applicationkey headers.
    'account_id' => env('CIN7_ACCOUNT_ID'),
    'application_key' => env('CIN7_APPLICATION_KEY'),

    // Local throttle for Cin7's limit of roughly 60 calls per minute per account.
    // Set `max` or `period` to 0 to remove the window; the 503 cooldown still applies.
    'rate_limit' => [
        'max' => (int) env('CIN7_RATE_MAX', 60),
        'period' => (int) env('CIN7_RATE_PERIOD', 60),

        // Unset falls back to `saloon.rate_limiter.store`, then `rate-limiter.default`.
        // Pick a store every worker and server shares, or the effective limit multiplies.
        'store' => env('CIN7_RATE_STORE'),
    ],

    // Bounded retries on HTTP 503; `times` is the total number of attempts.
    'retry' => [
        'times' => (int) env('CIN7_RETRY_TIMES', 4),
        'delay_ms' => (int) env('CIN7_RETRY_DELAY_MS', 5000),
    ],
];
