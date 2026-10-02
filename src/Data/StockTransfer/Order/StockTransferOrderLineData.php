<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTransfer\Order;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Concerns\HasProductFields;

/**
 * Stock Transfer Order Line Model, a line of a stock transfer order: the `TransferQuantity` of a
 * product, found by `ProductID` or `SKU`. `QuantityOnHand` and `QuantityAvailable` are read-only.
 * Like every object with a `ProductID`, it carries the product fields.
 *
 * @see docs/data.md
 */
final class StockTransferOrderLineData extends Data
{
    use HasProductFields;

    public function __construct(
        public float $TransferQuantity,
        #[RequiredWithout('SKU')]
        #[Uuid]
        public ?string $ProductID = null,
        #[RequiredWithout('ProductID')]
        #[Max(50)]
        public ?string $SKU = null,
        #[Max(1024)]
        public ?string $ProductName = null,
        public ?float $QuantityOnHand = null,
        public ?float $QuantityAvailable = null,
        #[Max(256)]
        public ?string $Comments = null,
    ) {
    }
}
