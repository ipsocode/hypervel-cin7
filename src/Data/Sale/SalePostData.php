<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Ipsocode\Cin7\Enums\ProcessType;

/**
 * The body of `sale` POST: the Sale POST/PUT Attributes without the `ID` only PUT takes, with
 * the POST-only `SaleType`. `AutoPickPackShipMode` is in the reference's POST example but in no
 * Sale table. The PUT body is `SalePutData`.
 *
 * @see docs/data.md
 */
final class SalePostData extends AbstractSaleData
{
    public function __construct(
        string $Location,
        float $CurrencyRate,
        public ?bool $TaxInclusive = null,
        public ?ProcessType $SaleType = null,
        public ?string $AutoPickPackShipMode = null,
    ) {
        parent::__construct($Location, $CurrencyRate);
    }
}
