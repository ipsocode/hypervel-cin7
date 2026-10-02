<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * The fields the Sale Model and the Sale POST/PUT Attributes share: the response of `sale` and
 * the body of its POST and PUT. Each is a final child that adds its own fields; a field declared
 * here is set through `from()`, not the child's constructor.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleData extends Data
{
    public ?string $ID = null;

    public ?string $Customer = null;

    public ?string $CustomerID = null;

    public ?string $Contact = null;

    public ?string $Phone = null;

    public ?string $Email = null;

    public ?string $DefaultAccount = null;

    public ?bool $SkipQuote = null;

    public ?AddressData $BillingAddress = null;

    public ?SaleShippingAddressData $ShippingAddress = null;

    public ?string $ShippingNotes = null;

    public ?string $TaxRule = null;

    public ?string $Terms = null;

    public ?string $PriceTier = null;

    public ?string $ShipBy = null;

    public ?string $Location = null;

    public ?string $SaleOrderDate = null;

    public ?string $LastModifiedOn = null;

    public ?string $Note = null;

    public ?string $CustomerReference = null;

    public ?string $Carrier = null;

    public ?float $CurrencyRate = null;

    public ?string $SalesRepresentative = null;

    public ?string $ExternalID = null;

    public ?AdditionalAttributeData $AdditionalAttributes = null;
}
