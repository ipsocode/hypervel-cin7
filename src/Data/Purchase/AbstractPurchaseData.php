<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\AdditionalAttributeData;
use Ipsocode\Cin7\Data\Other\AddressData;
use Ipsocode\Cin7\Data\Other\PurchaseShippingAddressData;
use Ipsocode\Cin7\Enums\TaxCalculation;

/**
 * The fields the Available Fields for Purchase table and the Purchase POST/PUT Attributes share:
 * the response of `purchase` and the body of its POST and PUT. Each is a final child that adds its
 * own fields.
 *
 * A purchase needs its `Approach` and `Location`, which the POST/PUT table requires, so each
 * child passes them to this constructor; the optional fields declared here are set through
 * `from()`. It also needs a supplier: `Supplier` or `SupplierID`, so a write body without either
 * fails validation before it is sent. `Approach` is `INVOICE` or `STOCK`, but the PUT example sends
 * `Stock`, so it is a string.
 *
 * @see docs/data.md
 */
abstract class AbstractPurchaseData extends Data
{
    #[RequiredWithout('Supplier')]
    #[Uuid]
    public ?string $SupplierID = null;

    #[RequiredWithout('SupplierID')]
    #[Max(256)]
    public ?string $Supplier = null;

    #[Max(256)]
    public ?string $Contact = null;

    #[Max(50)]
    public ?string $Phone = null;

    public ?bool $BlindReceipt = null;

    public ?AddressData $BillingAddress = null;

    public ?PurchaseShippingAddressData $ShippingAddress = null;

    #[Max(50)]
    public ?string $TaxRule = null;

    public ?TaxCalculation $TaxCalculation = null;

    #[Max(256)]
    public ?string $Terms = null;

    #[DateTime]
    public ?string $RequiredBy = null;

    #[Max(1024)]
    public ?string $Note = null;

    public ?float $CurrencyRate = null;

    public ?AdditionalAttributeData $AdditionalAttributes = null;

    public function __construct(
        #[Max(10)]
        public string $Approach,
        #[Max(256)]
        public string $Location,
    ) {
    }
}
