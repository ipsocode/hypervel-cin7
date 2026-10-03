<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Testing;

use Hypervel\Saloon\Http\Faking\MockResponse;

/**
 * Cin7-shaped `MockResponse`s for the tests of an application that uses the package.
 *
 * Lists keep Cin7's `Total`, `Page` and `<Thing>List` envelope and failures keep its Error Model,
 * so a test asserts on the keys the real API sends. Pass the responses to `Saloon::fake()`.
 *
 * @see docs/testing.md
 */
final class Cin7Fake
{
    /**
     * One page of a list in Cin7's `{Total, Page, <list key>}` envelope. `$total` defaults to this
     * page's item count, so a multi-page fake passes it.
     *
     * @param array<array-key, mixed> $items
     */
    public static function list(string $listKey, array $items = [], int $page = 1, ?int $total = null): MockResponse
    {
        return self::record([
            'Total' => $total ?? count($items),
            'Page' => $page,
            $listKey => $items,
        ]);
    }

    /**
     * One page of a list whose envelope has no `Total`, `{Page, <list key>}`, as
     * `ref/customer/credits` and `ref/supplier/deposits` answer.
     *
     * @param array<array-key, mixed> $items
     */
    public static function listWithoutTotal(string $listKey, array $items = [], int $page = 1): MockResponse
    {
        return self::record([
            'Page' => $page,
            $listKey => $items,
        ]);
    }

    /**
     * A successful response with this body.
     *
     * @param array<array-key, mixed> $body
     */
    public static function record(array $body): MockResponse
    {
        return MockResponse::make($body);
    }

    /**
     * Cin7's Error Model, `{ErrorCode, Exception}`. The status is the code unless `$status` names
     * another, and Cin7 sometimes sends the Error Model with a 200, which still throws.
     */
    public static function error(string $message, int $code = 400, ?int $status = null): MockResponse
    {
        return MockResponse::make(['ErrorCode' => $code, 'Exception' => $message], $status ?? $code);
    }

    /**
     * The 503 Cin7 returns when throttling, which carries no `Retry-After` header.
     */
    public static function throttled(): MockResponse
    {
        return self::error('Service Unavailable', 503);
    }

    /**
     * The 429 Cin7 documents for its 60 calls per minute limit, with a `Retry-After` of
     * `$retryAfter` seconds when given.
     */
    public static function limitReached(?int $retryAfter = null): MockResponse
    {
        $headers = $retryAfter === null ? [] : ['Retry-After' => (string) $retryAfter];

        return MockResponse::make(
            ['ErrorCode' => 429, 'Exception' => 'You reached 60 calls per minute API limit'],
            429,
            $headers,
        );
    }

    /**
     * The 403 Cin7 answers a wrong account ID or application key with.
     */
    public static function credentialsRejected(): MockResponse
    {
        return self::error('Incorrect credentials!', 403);
    }
}
