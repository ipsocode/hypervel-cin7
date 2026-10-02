<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Other\PurchaseUnStockLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The `Unstock` of a simple purchase's credit note, as the `purchase` examples send it: the
 * unstock task's `Status` and its `Lines` (Purchase Unstock Line Models), as the Purchase Stock
 * Model is the stock received's. No table documents it: the tables type `Unstock` as the list of
 * lines alone.
 *
 * @see docs/data.md
 */
final class PurchaseUnStockData extends Data
{
    /**
     * @param list<PurchaseUnStockLineData> $Lines
     */
    public function __construct(
        public TaskStatus $Status,
        #[DataCollectionOf(PurchaseUnStockLineData::class)]
        public array $Lines,
    ) {
    }
}
