<?php

declare(strict_types=1);

/*
 * This package's settings for the imported conventions check, .github/scripts/conventions.php,
 * run by initial.yml and `composer conventions`. The script's header lists the rules.
 */

return [
    // The prefix for __cin7.* context keys, cin7:* commands and store keys, cin7-* publish
    // tags, CIN7_* env vars and config/cin7.php.
    'slug' => 'cin7',

    // Paths no rule governs.
    'excluded' => [],

    // Namespaces banned beyond Illuminate\ and Laravel\, with the replacement. Guzzle
    // would bypass Saloon's rate limiter, retry policy and mock client.
    'banned_namespaces' => [
        'GuzzleHttp\\' => 'hypervel/saloon, the transport the connector is built on',
    ],

    // Function-name prefixes banned in shipped code, with the replacement.
    'banned_functions' => [
        'curl_' => 'hypervel/saloon, the transport the connector is built on',
    ],

    // Paths phpunit.xml's <source> may leave out of coverage.
    'coverage_excludes' => [],

    // Per-rule exceptions, '<path>' => [<exact hit count>, '<why>']; any count mismatch fails.
    'allowed' => [],
];
