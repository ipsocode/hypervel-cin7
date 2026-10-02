<?php

declare(strict_types=1);

namespace Workbench\App\Support;

/**
 * Cin7-shaped response bodies for the Workbench application and the test suite.
 *
 * Lists keep Cin7's `Total`, `Page` and `<Thing>List` envelope, so a test asserts on
 * the keys the real API sends.
 *
 * @see docs/testing.md
 */
final class Cin7Payloads
{
    /**
     * One page of customers. `$total` defaults to this page's item count, so a
     * multi-page fixture passes it.
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
     * One page of products. Unlike the other lists, the V2 envelope is keyed `Products`
     * (`{Total, Page, Products}`), not `ProductList`.
     *
     * @param list<array<string, mixed>> $products
     * @return array<string, mixed>
     */
    public static function products(array $products = [], int $page = 1, ?int $total = null): array
    {
        return [
            'Total' => $total ?? count($products),
            'Page' => $page,
            'Products' => $products,
        ];
    }

    /**
     * One page of a customer's credits. Unlike the other lists, the V2 envelope has no
     * `Total` (`{Page, CustomerCredits}`).
     *
     * @param list<array<string, mixed>> $credits
     * @return array<string, mixed>
     */
    public static function customerCredits(array $credits = [], int $page = 1): array
    {
        return [
            'Page' => $page,
            'CustomerCredits' => $credits,
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
     * The body of the 503 Cin7 returns when throttling; it carries no `Retry-After` header.
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
