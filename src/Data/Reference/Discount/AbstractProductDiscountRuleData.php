<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Reference\Discount;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;

/**
 * The fields of the Product Discount Rule table: the response of every `reference/discount` action
 * and the body of its POST and PUT. Each is a final child that adds the fields it requires.
 *
 * Every rule needs its `Name`, so each child passes it to this constructor; the optional fields
 * declared here are set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractProductDiscountRuleData extends Data
{
    /**
     * @var null|list<DiscountLineData>
     */
    #[DataCollectionOf(DiscountLineData::class)]
    public ?array $DiscountLines = null;

    public function __construct(
        #[Max(128)]
        public string $Name,
    ) {
    }
}
