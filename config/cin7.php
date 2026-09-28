<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Cin7 Core API client
|--------------------------------------------------------------------------
|
| Credentials and tuning for the Cin7 Core HTTP client. These
| values are read once, at container-resolve time, and passed to the
| connector explicitly — nothing in this package calls env() or getenv()
| at request time, which would be unsafe inside a Swoole worker.
|
| Consumers publish this file with:
|
|     php artisan vendor:publish --tag=cin7-config
|
| and may add their own keys to it (cache prefixes, business constants, …).
| mergeConfigFrom() keeps the published copy authoritative on shared keys.
|
*/

return [
    'account_id' => env('CIN7_ACCOUNT_ID'),
    'application_key' => env('CIN7_APPLICATION_KEY'),

    // Cin7 enforces roughly 60 calls per minute per account. The connector
    // throttles locally against the framework rate limiter so we queue rather
    // than collect 503s. Set `max` or `period` to 0 to disable throttling.
    'rate_limit' => [
        'max' => (int) env('CIN7_RATE_MAX', 60),
        'period' => (int) env('CIN7_RATE_PERIOD', 60),

        // The rate limiter store the window is kept in. Unset falls back to
        // `saloon.rate_limiter.store`, then to the application's default
        // store (`rate-limiter.default`, which ships as `database`). The limit
        // is only account-wide if every worker and server calling Cin7 shares
        // the store: `database` and `redis` do, `swoole` is shared by one
        // server's workers only, and `worker-array` by a single worker — it
        // multiplies the effective account limit by the worker count.
        'store' => env('CIN7_RATE_STORE'),
    ],

    // Cin7 signals throttling with HTTP 503. Retries are bounded — `times` is
    // the total number of attempts, not the number of extra ones.
    'retry' => [
        'times' => (int) env('CIN7_RETRY_TIMES', 4),
        'delay_ms' => (int) env('CIN7_RETRY_DELAY_MS', 5000),
    ],
];
