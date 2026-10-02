<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;

/**
 * Sale POST/PUT Attributes, the body of `sale` POST and PUT.
 *
 * `AutoPickPackShipMode` is in the reference's POST example but in no Sale table.
 *
 * @see docs/data.md
 */
final class SalePostPutData extends Data
{
    public function __construct(
        public ?string $ID = null,
        public ?string $Customer = null,
        public ?string $CustomerID = null,
        public ?string $Contact = null,
        public ?string $Phone = null,
        public ?string $Email = null,
        public ?string $DefaultAccount = null,
        public ?bool $SkipQuote = null,
        public ?AddressData $BillingAddress = null,
        public ?SaleShippingAddressData $ShippingAddress = null,
        public ?string $ShippingNotes = null,
        public ?string $TaxRule = null,
        public ?bool $TaxInclusive = null,
        public ?string $Terms = null,
        public ?string $PriceTier = null,
        public ?string $ShipBy = null,
        public ?string $Location = null,
        public ?string $SaleOrderDate = null,
        public ?string $LastModifiedOn = null,
        public ?string $Note = null,
        public ?string $CustomerReference = null,
        public ?float $CurrencyRate = null,
        public ?string $SalesRepresentative = null,
        public ?string $Carrier = null,
        public ?string $ExternalID = null,
        public ?AdditionalAttributeData $AdditionalAttributes = null,
        public ?string $SaleType = null,
        public ?string $AutoPickPackShipMode = null,
    ) {
    }
}
