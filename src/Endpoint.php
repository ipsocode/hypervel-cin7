<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

use Hypervel\Saloon\Enums\Method;
use ValueError;

/**
 * The Cin7 endpoints this client knows about, keyed by the accessor name callers pass in.
 *
 * A new endpoint is a case plus its rows in the matches below, never a new class.
 *
 * @see docs/endpoints.md
 */
enum Endpoint: string
{
    case Customer = 'customer';
    case Product = 'product';
    case Sale = 'sale';
    case SaleInvoice = 'saleInvoice';
    case SaleOrder = 'saleOrder';
    case SaleCreditNote = 'saleCreditNote';
    case SalePayment = 'salePayment';
    case SaleList = 'saleList';
    case Tax = 'tax';
    case MoneyOperation = 'moneyOperation';
    case CustomerCredits = 'customerCredits';

    /**
     * Resolve an endpoint from its case value, case-sensitively.
     *
     * @throws ValueError when the accessor is unknown or empty
     */
    public static function fromAccessor(string $accessor): self
    {
        return self::from($accessor);
    }

    /**
     * The path appended to the API base URL.
     */
    public function path(): string
    {
        return match ($this) {
            self::Customer => 'customer',
            self::Product => 'product',
            self::Sale => 'sale',
            self::SaleInvoice => 'sale/invoice',
            self::SaleOrder => 'sale/order',
            self::SaleCreditNote => 'sale/creditnote',
            self::SalePayment => 'sale/payment',
            self::SaleList => 'saleList',
            self::Tax => 'ref/tax',
            self::MoneyOperation => 'moneyOperation',
            self::CustomerCredits => 'ref/customer/credits',
        };
    }

    /**
     * The field a find or update carries the GUID under.
     */
    public function guidKey(): string
    {
        return match ($this) {
            self::SaleInvoice, self::SaleOrder, self::SaleCreditNote, self::SaleList => 'SaleID',
            self::CustomerCredits => 'CustomerID',
            default => 'ID',
        };
    }

    /**
     * The field a delete carries the GUID under.
     *
     * `ID` on every endpoint modelled here, even the `sale/*` ones that find by `SaleID`.
     */
    public function deleteGuidKey(): string
    {
        return 'ID';
    }

    /**
     * The write verbs this endpoint accepts; GET is granted by the base request and never listed.
     *
     * @return list<Method>
     */
    public function writeMethods(): array
    {
        return match ($this) {
            self::Customer, self::Product, self::Tax => [Method::POST, Method::PUT],
            self::Sale, self::SalePayment, self::MoneyOperation => [Method::POST, Method::PUT, Method::DELETE],
            self::SaleInvoice, self::SaleCreditNote => [Method::POST, Method::DELETE],
            self::SaleOrder => [Method::POST],
            self::SaleList, self::CustomerCredits => [],
        };
    }
}
