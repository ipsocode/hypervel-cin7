<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;

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
        public string|Optional $ID,
        public string|Optional $Customer,
        public string|Optional $CustomerID,
        public string|Optional $Contact,
        public string|Optional $Phone,
        public string|Optional $Email,
        public string|Optional $DefaultAccount,
        public bool|Optional $SkipQuote,
        public AddressData|Optional $BillingAddress,
        public SaleShippingAddressData|Optional $ShippingAddress,
        public string|Optional $ShippingNotes,
        public string|Optional $TaxRule,
        public bool|Optional $TaxInclusive,
        public string|Optional $Terms,
        public string|Optional $PriceTier,
        public string|Optional $ShipBy,
        public string|Optional $Location,
        public string|Optional $SaleOrderDate,
        public string|Optional $LastModifiedOn,
        public string|Optional $Note,
        public string|Optional $CustomerReference,
        public float|Optional $CurrencyRate,
        public string|Optional $SalesRepresentative,
        public string|Optional $Carrier,
        public string|Optional|null $ExternalID,
        public AdditionalAttributeData|Optional $AdditionalAttributes,
        public string|Optional $SaleType,
        public string|Optional $AutoPickPackShipMode,
    ) {
    }
}
