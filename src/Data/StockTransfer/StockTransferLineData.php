<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTransfer;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Concerns\HasProductFields;

/**
 * Stock Transfer Line Model, a line of a stock transfer's `Lines`: the `TransferQuantity` of a
 * product to move, found by `ProductID` or `SKU`. `QuantityOnHand` and `QuantityAvailable` are
 * read-only. `BatchSN` is required when the product's costing method is not FIFO, and `ExpiryDate`
 * for `FEFO - Serial Number`; the reference writes them as "Yes*" and a rule that depends on the
 * product, so they stay optional. Like every object with a `ProductID`, it carries the product
 * fields.
 *
 * @see docs/data.md
 */
final class StockTransferLineData extends Data
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
        #[Max(50)]
        public ?string $BatchSN = null,
        #[DateTime]
        public ?string $ExpiryDate = null,
        #[Max(256)]
        public ?string $Comments = null,
    ) {
    }
}
