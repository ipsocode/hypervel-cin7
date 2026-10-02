<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data;

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
 * The fields the Available Fields for Purchase tables and the Purchase POST/PUT Attributes share:
 * the response of `purchase` and of `advanced-purchase` and the body of their POST and PUT. The
 * two paths document these fields alike, so the simple and the advanced purchase share this
 * parent, which is in `src/Data/` itself as its children span both families. Each is a final
 * child that adds its own fields.
 *
 * A purchase needs its `Location`, which the POST/PUT tables require, so each child passes it to
 * this constructor; the optional fields declared here are set through `from()`. The tables require
 * `Approach` too, but the `advanced-purchase` PUT example sends none, so each child declares it:
 * every class requires it but `AdvancedPurchasePutData`. `Approach` is `INVOICE` or `STOCK`, but
 * the `purchase` PUT and `advanced-purchase` POST examples send `Stock`, so it is a string. A
 * purchase also needs a supplier: `Supplier` or `SupplierID`, so a write body without either fails
 * validation before it is sent.
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
        #[Max(256)]
        public string $Location,
    ) {
    }
}
