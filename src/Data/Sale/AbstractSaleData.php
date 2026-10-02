<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\Validation\Date;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;

/**
 * The fields the Sale Model and the Sale POST/PUT Attributes share: the response of `sale` and
 * the body of its POST and PUT. Each is a final child that adds its own fields.
 *
 * A sale needs its `Location` and `CurrencyRate`, so each child passes them to this constructor;
 * the optional fields declared here are set through `from()`. It also needs a customer: `Customer`
 * or `CustomerID`, so a write body without either fails validation before it is sent.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleData extends Data
{
    #[RequiredWithout('CustomerID')]
    #[Max(256)]
    public ?string $Customer = null;

    #[RequiredWithout('Customer')]
    #[Uuid]
    public ?string $CustomerID = null;

    #[Max(256)]
    public ?string $Contact = null;

    #[Max(50)]
    public ?string $Phone = null;

    #[Max(256)]
    public ?string $Email = null;

    #[Max(50)]
    public ?string $DefaultAccount = null;

    public ?bool $SkipQuote = null;

    public ?AddressData $BillingAddress = null;

    public ?SaleShippingAddressData $ShippingAddress = null;

    #[Max(1024)]
    public ?string $ShippingNotes = null;

    #[Max(50)]
    public ?string $TaxRule = null;

    #[Max(256)]
    public ?string $Terms = null;

    #[Max(50)]
    public ?string $PriceTier = null;

    #[Date]
    public ?string $ShipBy = null;

    #[Date]
    public ?string $SaleOrderDate = null;

    #[DateTime]
    public ?string $LastModifiedOn = null;

    #[Max(1024)]
    public ?string $Note = null;

    #[Max(256)]
    public ?string $CustomerReference = null;

    #[Max(256)]
    public ?string $Carrier = null;

    #[Max(256)]
    public ?string $SalesRepresentative = null;

    #[Max(256)]
    public ?string $ExternalID = null;

    public ?AdditionalAttributeData $AdditionalAttributes = null;

    public function __construct(
        #[Max(256)]
        public string $Location,
        public float $CurrencyRate,
    ) {
    }
}
