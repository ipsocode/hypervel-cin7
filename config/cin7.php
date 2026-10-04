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
    // Sent as the api-auth-accountid and api-auth-applicationkey headers. These two keys are
    // the implicit `default` connection, so a single account needs nothing else.
    'account_id' => env('CIN7_ACCOUNT_ID'),
    'application_key' => env('CIN7_APPLICATION_KEY'),

    // The connection `Cin7Connector` injection and `Cin7Manager::connection()` resolve.
    'default' => env('CIN7_CONNECTION', 'default'),

    // More accounts, or more API applications, by name: `account_id`, `application_key` and
    // an optional `rate_limit` block, whose missing keys are the top-level ones below. Each
    // connection throttles and cools down on its own.
    'connections' => [
        // 'sandbox' => [
        //     'account_id' => env('CIN7_SANDBOX_ACCOUNT_ID'),
        //     'application_key' => env('CIN7_SANDBOX_APPLICATION_KEY'),
        //     'rate_limit' => ['max' => 30],
        // ],
    ],

    // Local throttle for Cin7's limit of 60 calls per minute per API application.
    // Set `max` or `period` to 0 to remove the window; the throttling cooldown still applies.
    'rate_limit' => [
        'max' => (int) env('CIN7_RATE_MAX', 60),
        'period' => (int) env('CIN7_RATE_PERIOD', 60),

        // Unset falls back to `saloon.rate_limiter.store`, then `rate-limiter.default`.
        // Pick a store every worker and server shares, or the effective limit multiplies.
        'store' => env('CIN7_RATE_STORE'),
    ],

    // Bounded retries on HTTP 429 and 503; `times` is the total number of attempts.
    'retry' => [
        'times' => (int) env('CIN7_RETRY_TIMES', 4),
        'delay_ms' => (int) env('CIN7_RETRY_DELAY_MS', 5000),
    ],

    // A scheduled copy of Cin7's records in one local table, off by default. Off, the package
    // creates no table, schedules nothing and runs no query. See docs/sync.md.
    'sync' => [
        'enabled' => (bool) env('CIN7_SYNC', false),

        // One time for every module that can ask Cin7 for only what changed, as a cron expression.
        // The reference books, which Cin7 sends only whole, wait for the full pull below or for
        // `cin7:sync`. Null schedules no common pull.
        'cron' => env('CIN7_SYNC_CRON', '0 * * * *'),

        // The modules pulled at that time, in this order: what records point to before them, and
        // a document module after its list. '*' is every module in that order: the reference
        // books (ref/account, ref/account/bank, ref/location, ref/tax, ref/paymentterm,
        // ref/category, ref/brand, ref/unit, ref/carrier, ref/attributeset,
        // ref/fixedassettype), then customer, supplier, product, saleList, purchaseList, sale and
        // advanced-purchase.
        'modules' => '*',

        // Exceptions: a module pulled at a time of its own as well, usually a more frequent one.
        // Not a reference book: those are read whole, so only the full pull reads them.
        'exceptions' => [
            // 'saleList' => '*/5 * * * *',
        ],

        // A full pull of every module, the reference books included, which also removes what Cin7
        // no longer returns. Null: never.
        'full' => env('CIN7_SYNC_FULL', '0 2 * * 0'),

        // Minutes an incremental pull reaches back.
        'lookback' => (int) env('CIN7_SYNC_LOOKBACK', 1440),

        // Records a page, 1 to 1000.
        'limit' => (int) env('CIN7_SYNC_LIMIT', 500),

        // Milliseconds between the sync's own calls, which share the rate-limit window.
        'pause_ms' => (int) env('CIN7_SYNC_PAUSE_MS', 1000),

        // Documents read per module per run, one call each.
        'documents' => (int) env('CIN7_SYNC_DOCUMENTS', 250),

        // The queue the scheduled pulls go on; null is the connection's default queue.
        'queue' => env('CIN7_SYNC_QUEUE'),

        // Seconds a queued pull may run.
        'timeout' => (int) env('CIN7_SYNC_TIMEOUT', 3600),

        // The database connection the table lives on; null is the default one.
        'connection' => env('CIN7_SYNC_CONNECTION'),
    ],
];
