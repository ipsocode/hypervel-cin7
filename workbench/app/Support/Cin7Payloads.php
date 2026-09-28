<?php

declare(strict_types=1);

namespace Workbench\App\Support;

/**
 * Cin7-shaped response bodies for the Workbench application.
 *
 * A package that persists data would keep models and factories here, because
 * that is the shape its consumers hand it. This package's consumers hand it
 * nothing — what they get back is JSON from Cin7 — so the equivalent fixture
 * is the response envelope, spelled out once here rather than re-guessed
 * inline in every test.
 *
 * The envelope matters: Cin7 wraps a list in `Total`, `Page` and a
 * `<Thing>List` key, so a test asserting on `json()['CustomerList']` is
 * asserting on something the real API would send. An inline `['Customers' => []]`
 * would pass just as well against a client that decoded the wrong key.
 */
final class Cin7Payloads
{
    /**
     * A paginated customer list, keyed the way Cin7 keys it.
     *
     * `$total` defaults to this page's own item count — the shape every
     * existing single-page test relies on — but a multi-page fixture passes
     * the full matching record count explicitly, since a page rarely carries
     * every matching record.
     *
     * @param list<array<string, mixed>> $customers
     * @return array<string, mixed>
     */
    public static function customerList(array $customers = [], int $page = 1, ?int $total = null): array
    {
        return [
            'Total' => $total ?? count($customers),
            'Page' => $page,
            'CustomerList' => $customers,
        ];
    }

    /**
     * One customer record, as it appears inside `CustomerList`.
     *
     * @return array<string, mixed>
     */
    public static function customer(string $id = '11111111-2222-3333-4444-555555555555', string $name = 'ACME'): array
    {
        return [
            'ID' => $id,
            'Name' => $name,
            'Status' => 'Active',
            'Currency' => 'GBP',
        ];
    }

    /**
     * A sale, which Cin7 keys by `SaleID` rather than `ID`.
     *
     * @return array<string, mixed>
     */
    public static function sale(string $saleId = '99999999-8888-7777-6666-555555555555'): array
    {
        return [
            'SaleID' => $saleId,
            'Status' => 'AUTHORISED',
            'InvoiceNumber' => 'INV-0001',
        ];
    }

    /**
     * The body Cin7 returns when it is throttling.
     *
     * It answers with a 503 and this envelope — no `Retry-After` header, which
     * is exactly why Cin7Connector::resolveRateLimitCooldown() cannot fall
     * through to Saloon's default parser.
     *
     * @return array<string, mixed>
     */
    public static function throttled(): array
    {
        return ['Errors' => ['Service Unavailable']];
    }

    /**
     * The body Cin7 returns for a rejected request.
     *
     * @return array<string, mixed>
     */
    public static function error(string $message = 'Request is invalid'): array
    {
        return ['Errors' => [$message]];
    }
}
