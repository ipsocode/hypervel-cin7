<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Other\PurchaseAdditionalChargeData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The fields the Purchase Order Model and the Available Fields for Purchase Order table share: a
 * purchase's `Order`, the response of `purchase/order` and the body of its POST. Each is a final
 * child that adds its own fields.
 *
 * Every order needs its `Status` and `Lines`, so each child passes them to this constructor;
 * `AdditionalCharges` is set through `from()`. Both tables require `Memo` too, but the `purchase`
 * examples embed an order with a `null` `Memo`, so each child declares it: the POST body requires
 * it, and the response leaves it optional.
 *
 * @see docs/data.md
 */
abstract class AbstractPurchaseOrderData extends Data
{
    /**
     * @var null|list<PurchaseAdditionalChargeData>
     */
    #[DataCollectionOf(PurchaseAdditionalChargeData::class)]
    public ?array $AdditionalCharges = null;

    /**
     * @param list<PurchaseOrderLineData> $Lines
     */
    public function __construct(
        public TaskStatus $Status,
        #[DataCollectionOf(PurchaseOrderLineData::class)]
        public array $Lines,
    ) {
    }
}
