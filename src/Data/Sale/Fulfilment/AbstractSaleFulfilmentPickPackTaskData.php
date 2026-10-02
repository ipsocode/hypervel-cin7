<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;

/**
 * The fields the Sale Fulfilment Pick and Sale Fulfilment Pack tables share: a fulfilment task's
 * pick or pack as `sale/fulfilment/pick` and `sale/fulfilment/pack` answer and take it. Each is a
 * final child that adds its `Status`.
 *
 * Both tables require the fulfilment's `TaskID`, so each child passes it to this constructor;
 * `Lines` is set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleFulfilmentPickPackTaskData extends Data
{
    /**
     * @var null|list<SaleFulfilmentPickPackLineData>
     */
    #[DataCollectionOf(SaleFulfilmentPickPackLineData::class)]
    public ?array $Lines = null;

    public function __construct(
        #[Uuid]
        public string $TaskID,
    ) {
    }
}
