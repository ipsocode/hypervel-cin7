<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Tax;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;

/**
 * The fields of the Tax table: the response of `ref/tax` and the body of its POST and PUT. Each
 * is a final child that adds its `ID`, or none.
 *
 * Every tax rule needs its `Name`, `Account`, `IsActive` and `TaxInclusive`, so each child passes
 * them to this constructor; the optional fields declared here are set through `from()`.
 * `TaxPercent` is read-only, but the reference's request examples send it, so it is modelled,
 * and the requests leave it out of the body.
 *
 * @see docs/data.md
 */
abstract class AbstractTaxData extends Data
{
    public ?float $TaxPercent = null;

    public ?bool $IsTaxForSale = null;

    public ?bool $IsTaxForPurchase = null;

    /**
     * @var null|list<TaxComponentData>
     */
    #[DataCollectionOf(TaxComponentData::class)]
    public ?array $Components = null;

    public function __construct(
        #[Max(50)]
        public string $Name,
        public string $Account,
        public bool $IsActive,
        public bool $TaxInclusive,
    ) {
    }
}
