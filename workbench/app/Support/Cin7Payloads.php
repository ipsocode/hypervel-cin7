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
     * The `ref/tax` GET example from the V2 reference: two rules, the first of which has a
     * component with no `Compound` key.
     *
     * @return array<string, mixed>
     */
    public static function taxList(): array
    {
        return [
            'Total' => 2,
            'Page' => 1,
            'TaxRuleList' => [
                [
                    'ID' => '9d707beb-19cf-4d7b-a5d9-9eaff05c504c',
                    'Name' => 'GST on Income',
                    'Account' => '820',
                    'IsActive' => true,
                    'TaxInclusive' => false,
                    'TaxPercent' => 10,
                    'IsTaxForSale' => true,
                    'IsTaxForPurchase' => false,
                    'Components' => [
                        [
                            'ID' => 'B1151EEA-3364-4534-88E6-DF2C6B59A6D8',
                            'Name' => 'Tax',
                            'Percent' => '10.0000000000',
                            'AccountCode' => '0',
                            'ComponentOrder' => '1',
                        ],
                    ],
                ],
                [
                    'ID' => 'd4d53cdf-4b87-4b67-9c17-733fc5419a2a',
                    'Name' => 'Tax test',
                    'Account' => '820',
                    'IsActive' => true,
                    'TaxInclusive' => false,
                    'TaxPercent' => 0,
                    'IsTaxForSale' => false,
                    'IsTaxForPurchase' => true,
                    'Components' => [
                        [
                            'ID' => '534AC4D9-6F37-448A-971E-DF1C2B21FB64',
                            'Name' => 'No Tax',
                            'Percent' => '0.0000000000',
                            'AccountCode' => '',
                            'Compound' => '0',
                            'ComponentOrder' => '1',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * The `ref/tax` POST example response from the V2 reference: one saved rule with two
     * components. The PUT response has the same shape.
     *
     * @return array<string, mixed>
     */
    public static function taxSaved(): array
    {
        return [
            'Total' => 1,
            'Page' => 1,
            'TaxRuleList' => [
                [
                    'ID' => '24551562-ebd1-4294-a04a-3ab258f5e541',
                    'Name' => 'Post test',
                    'Account' => '800',
                    'IsActive' => true,
                    'TaxInclusive' => false,
                    'TaxPercent' => 35,
                    'IsTaxForSale' => true,
                    'IsTaxForPurchase' => true,
                    'Components' => [
                        [
                            'ID' => '0119E1C0-504A-429B-B4E2-4EEC02A9E88A',
                            'Name' => 'Tax 1st',
                            'Percent' => '10.0000000000',
                            'AccountCode' => '800',
                            'Compound' => '1',
                            'ComponentOrder' => '1',
                        ],
                        [
                            'ID' => '0B6D5C0F-D8EF-41FF-86D2-A05E5616FC61',
                            'Name' => 'Tax 2nd',
                            'Percent' => '15.0000000000',
                            'AccountCode' => '800',
                            'Compound' => '0',
                            'ComponentOrder' => '2',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * The `ref/customer/credits` GET example from the V2 reference.
     *
     * @return array<string, mixed>
     */
    public static function customerCreditsExample(): array
    {
        return [
            'Page' => 1,
            'CustomerCredits' => [
                [
                    'CreditID' => '55607b9a-dc9b-4ef4-9ed3-495434e90434',
                    'CustomerID' => 'ce607b9a-dc9b-4ef4-9ed3-495434e90467',
                    'CustomerName' => 'Customer name',
                    'Account' => '1200',
                    'Amount' => 100,
                    'RemainingAmount' => 50,
                    'Currency' => 'USD',
                    'ConvRate' => 1,
                    'Date' => '2024-01-09T00:00:00',
                    'Description' => 'Credit by prepayments',
                ],
                [
                    'CreditID' => '67607b9a-dc9b-4ef4-9ed3-495434e90489',
                    'CustomerID' => 'ce607b9a-dc9b-4ef4-9ed3-495434e90467',
                    'CustomerName' => 'Customer name',
                    'Account' => '1300',
                    'Amount' => 100,
                    'RemainingAmount' => 0,
                    'Currency' => 'USD',
                    'ConvRate' => 1,
                    'Date' => '2024-04-09T00:00:00',
                    'Description' => 'Credit by prepayments',
                ],
            ],
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
