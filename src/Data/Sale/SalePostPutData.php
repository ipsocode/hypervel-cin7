<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\Validation\Max;

/**
 * Sale POST/PUT Attributes, the body of `sale` POST and PUT.
 *
 * `AutoPickPackShipMode` is in the reference's POST example but in no Sale table.
 *
 * @see docs/data.md
 */
final class SalePostPutData extends AbstractSaleData
{
    public function __construct(
        string $Location,
        float $CurrencyRate,
        public ?bool $TaxInclusive = null,
        #[Max(50)]
        public ?string $SaleType = null,
        public ?string $AutoPickPackShipMode = null,
    ) {
        parent::__construct($Location, $CurrencyRate);
    }
}
