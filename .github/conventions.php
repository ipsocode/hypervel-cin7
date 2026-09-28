<?php

declare(strict_types=1);

/*
 * This package's settings for the conventions check, .github/scripts/conventions.php,
 * which initial.yml runs ahead of the test jobs and `composer conventions` runs locally.
 * The check is imported, the same in every ipsocode/hypervel-* package; this file is
 * the part that is this package's own. The script's header lists the rules.
 */

return [
    // The name this package writes under wherever the application writes too:
    // __cin7.* context keys, cin7:* commands and store keys, cin7-* publish tags,
    // CIN7_* env vars and config/cin7.php.
    'slug' => 'cin7',

    // Paths under the package root that no rule governs.
    'excluded' => [],

    // Namespaces banned on top of Illuminate\ and Laravel\, each with what to use
    // instead. The connector's transport is hypervel/saloon; a hand-rolled Guzzle
    // client would bypass its rate limiter, retry policy and mock client.
    'banned_namespaces' => [
        'GuzzleHttp\\' => 'hypervel/saloon, the transport the connector is built on',
    ],

    // Function-name prefixes banned in shipped code, each with what to use instead.
    'banned_functions' => [
        'curl_' => 'hypervel/saloon, the transport the connector is built on',
    ],

    // Paths phpunit.xml's <source> may leave out of the coverage gate.
    'coverage_excludes' => [],

    // The exceptions, per rule: '<path>' => [<exact number of hits>, '<why>']. The
    // check fails as soon as a count stops matching, either way.
    'allowed' => [],
];
