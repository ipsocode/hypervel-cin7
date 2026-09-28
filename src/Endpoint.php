<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

use Hypervel\Saloon\Enums\Method;
use ValueError;

/**
 * The Cin7 endpoints this client knows about.
 *
 * `eighteen73/dear-api` modelled these as 74 concrete classes, each encoding
 * two or three strings. They are data, so they live here as data: the case
 * value is the accessor name that package exposed through `__call()`, so
 * existing callers can pass their endpoint strings through untouched.
 *
 * Adding one of the remaining Cin7 endpoints means adding a case plus its
 * rows below — never a new class. Two quirks are worth carrying over when
 * those cases land: `Account` is keyed by `Code` for find, update *and*
 * delete — the only upstream endpoint whose delete key is not `ID` — and
 * `ProductMarkupPrices` finds and updates under `ProductID`.
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
     * Resolve an endpoint from the accessor name used by the caller.
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
     * Upstream defaulted this to `ID` for every endpoint and none of the ones
     * modelled here overrode it — so the `sale/*` sub-endpoints find by
     * `SaleID` but delete by `ID`. That asymmetry is Cin7's observed behavior
     * and is preserved deliberately; it is one line to correct if the API
     * reference ever says otherwise.
     */
    public function deleteGuidKey(): string
    {
        return 'ID';
    }

    /**
     * The write verbs this endpoint accepts. GET is always allowed.
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
