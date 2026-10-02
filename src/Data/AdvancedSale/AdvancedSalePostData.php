<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\AdvancedSale;

use Ipsocode\Cin7\Data\Sale\AbstractSaleData;
use Ipsocode\Cin7\Enums\ProcessType;

/**
 * The body of `sale` POST for an advanced sale, one that holds several fulfilments, invoices and
 * credit notes. It is `SalePostData` with the POST-only `SaleType` fixed to `Advanced`, which
 * `toArray()` adds, so it is no property to set or to get wrong. The reference has no endpoint of
 * its own for it: `advancedSale()` sends it through `sale`.
 *
 * @see docs/data.md
 */
final class AdvancedSalePostData extends AbstractSaleData
{
    public function __construct(
        string $Location,
        float $CurrencyRate,
        public ?bool $TaxInclusive = null,
        public ?string $AutoPickPackShipMode = null,
    ) {
        parent::__construct($Location, $CurrencyRate);
    }

    /**
     * @return array<array-key, mixed>
     */
    public function toArray(): array
    {
        return [...parent::toArray(), 'SaleType' => ProcessType::Advanced->value];
    }
}
