<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `sale` PUT: the Sale POST/PUT Attributes with the `ID` PUT requires, without the
 * POST-only `SaleType` and `AutoPickPackShipMode`. The POST body is `SalePostData`.
 *
 * @see docs/data.md
 */
final class SalePutData extends AbstractSaleData
{
    public function __construct(
        string $Location,
        float $CurrencyRate,
        #[Uuid]
        public string $ID,
        public ?bool $TaxInclusive = null,
    ) {
        parent::__construct($Location, $CurrencyRate);
    }
}
