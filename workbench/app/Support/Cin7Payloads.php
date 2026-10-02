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
     * A JSON fixture under `workbench/fixtures/`, by API path and name: `load('sale/invoice',
     * 'get.response')` reads `workbench/fixtures/sale/invoice/get.response.json`.
     *
     * @return array<array-key, mixed>
     */
    public static function load(string $path, string $name): array
    {
        $file = dirname(__DIR__, 2) . '/fixtures/' . $path . '/' . $name . '.json';

        return json_decode((string) file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
    }

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
            'PaymentTerm' => '30 days',
            'AccountReceivable' => '610',
            'RevenueAccount' => '200',
            'TaxRule' => 'Tax Exempt',
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
     * The `customer` GET example from the V2 reference, whole: one customer with an address,
     * a contact and a child customer, and one with a parent, a contact and a product price.
     *
     * @return array<string, mixed>
     */
    public static function customerExample(): array
    {
        return self::load('customer', 'get.response');
    }

    /**
     * The `product` GET example from the V2 reference, whole, with every nested list populated
     * except `Suppliers`, which is empty in the reference. The reference's example has an
     * unclosed quote on the movement's `Date`; it is closed here.
     *
     * @return array<string, mixed>
     */
    public static function productExample(): array
    {
        return self::load('product', 'get.response');
    }

    /**
     * A POST or PUT `customer` response: the list envelope holding the one saved customer.
     *
     * @return array<string, mixed>
     */
    public static function customerSaved(): array
    {
        return self::load('customer', 'post.response');
    }

    /**
     * A POST or PUT `product` response: the list envelope holding the one saved product.
     *
     * @return array<string, mixed>
     */
    public static function productSaved(): array
    {
        return self::load('product', 'post.response');
    }

    /**
     * The `ref/tax` GET example from the V2 reference: two rules, the first of which has a
     * component with no `Compound` key.
     *
     * @return array<string, mixed>
     */
    public static function taxList(): array
    {
        return self::load('ref/tax', 'get.response');
    }

    /**
     * The `ref/tax` POST example response from the V2 reference: one saved rule with two
     * components. The PUT response has the same shape.
     *
     * @return array<string, mixed>
     */
    public static function taxSaved(): array
    {
        return self::load('ref/tax', 'post.response');
    }

    /**
     * The `ref/customer/credits` GET example from the V2 reference.
     *
     * @return array<string, mixed>
     */
    public static function customerCreditsExample(): array
    {
        return self::load('ref/customer/credits', 'get.response');
    }

    /**
     * The `sale` GET example from the V2 reference: a sale keyed by `ID`, carrying every
     * nested model (quote, order, fulfilment, invoice, credit note, journals, attachments,
     * inventory movements and transactions). The `ID` is replaceable.
     *
     * @return array<string, mixed>
     */
    public static function sale(?string $id = null): array
    {
        $sale = self::load('sale', 'get.response');

        return $id === null ? $sale : ['ID' => $id] + $sale;
    }

    /**
     * The `saleList` GET example from the V2 reference: two sales, with the nulls it sends.
     *
     * @return array<string, mixed>
     */
    public static function saleList(): array
    {
        return self::load('saleList', 'get.response');
    }

    /**
     * The Sale Order of the `sale` GET example, as `sale/order` answers it.
     *
     * @return array<string, mixed>
     */
    public static function saleOrder(): array
    {
        return self::load('sale/order', 'get.response');
    }

    /**
     * Sale Invoice Partial Model, the invoice of the reference's `sale/invoice` GET example.
     *
     * @return array<string, mixed>
     */
    public static function saleInvoicePartial(): array
    {
        return self::saleInvoices()['Invoices'][0];
    }

    /**
     * The `{SaleID, Invoices}` envelope `sale/invoice` answers with.
     *
     * @return array<string, mixed>
     */
    public static function saleInvoices(): array
    {
        return self::load('sale/invoice', 'get.response');
    }

    /**
     * Sale Invoice POST Model, the reference's `sale/invoice` POST example.
     *
     * @return array<string, mixed>
     */
    public static function saleInvoicePost(): array
    {
        return self::load('sale/invoice', 'post.request');
    }

    /**
     * The reference's `sale/invoice` PUT example, its trailing comma removed.
     *
     * @return array<string, mixed>
     */
    public static function saleInvoicePut(): array
    {
        return self::load('sale/invoice', 'put.request');
    }

    /**
     * Sale Credit Note Invoice Partial Model, the credit note of the reference's `sale/creditnote`
     * GET example, with the `CreditNoteBalance` and `Payments` that `IncludePaymentInfo` adds.
     *
     * @return array<string, mixed>
     */
    public static function saleCreditNotePartial(): array
    {
        return self::saleCreditNotes()['CreditNotes'][0];
    }

    /**
     * The `{SaleID, CreditNotes}` envelope `sale/creditnote` answers with.
     *
     * @return array<string, mixed>
     */
    public static function saleCreditNotes(): array
    {
        return self::load('sale/creditnote', 'get.response');
    }

    /**
     * Sale Credit Note POST Model, the reference's `sale/creditnote` POST example, its unquoted
     * `SaleID` key and trailing comma fixed.
     *
     * @return array<string, mixed>
     */
    public static function saleCreditNotePost(): array
    {
        return self::load('sale/creditnote', 'post.request');
    }

    /**
     * Sale Payment Line Partial Model, the payment of the reference's `sale/payment` GET example.
     *
     * @return array<string, mixed>
     */
    public static function salePayment(): array
    {
        return self::salePayments()[0];
    }

    /**
     * The bare array `sale/payment` GET answers with: the reference's example, a payment and a refund.
     *
     * @return list<array<string, mixed>>
     */
    public static function salePayments(): array
    {
        return self::load('sale/payment', 'get.response');
    }

    /**
     * The reference's `sale/payment` POST example, without the PUT-only `CreditID`.
     *
     * @return array<string, mixed>
     */
    public static function salePaymentPost(): array
    {
        return self::load('sale/payment', 'post.request');
    }

    /**
     * The reference's `sale/payment` PUT example.
     *
     * @return array<string, mixed>
     */
    public static function salePaymentPut(): array
    {
        return self::load('sale/payment', 'put.request');
    }

    /**
     * The `{Total, Page, MoneyTasks}` envelope `moneyTaskList` answers with, from the reference's example.
     *
     * @return array<string, mixed>
     */
    public static function moneyTaskList(): array
    {
        return self::load('moneyTaskList', 'get.response');
    }

    /**
     * Money Task, copied from the reference's `moneyOperation` GET example.
     *
     * @return array<string, mixed>
     */
    public static function moneyTask(): array
    {
        return self::load('moneyOperation', 'get.response');
    }

    /**
     * An Error Model body for the 503 Cin7 returns when throttling; it carries no `Retry-After`
     * header.
     *
     * @return array<string, mixed>
     */
    public static function throttled(): array
    {
        return self::error('Service Unavailable', 503);
    }

    /**
     * An Error Model body for the 429 Cin7 documents for its 60 calls per minute limit.
     *
     * @return array<string, mixed>
     */
    public static function limitReached(): array
    {
        return self::error('You reached 60 calls per minute API limit', 429);
    }

    /**
     * Error Model, `{ErrorCode, Exception}`, the body Cin7 returns for a failed call.
     *
     * @return array<string, mixed>
     */
    public static function error(string $message = 'Request is invalid', int $code = 400): array
    {
        return ['ErrorCode' => $code, 'Exception' => $message];
    }
}
