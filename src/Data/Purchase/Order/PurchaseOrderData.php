<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Required;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Other\SalePaymentLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Purchase Order Model, the `Order` of a purchase and an advanced purchase, and the Available
 * Fields for Purchase Order table, the response of `purchase/order`, which adds `TaskID` and
 * `CombineAdditionalCharges`. One name, so one class with the union of both: the second table
 * requires them, but the `purchase` examples embed the order without them, so they are nullable and
 * `#[Required]`, which only a write body checks; the `Prepayments`, which only the first table has,
 * are optional. Both tables require `Memo`, but the `purchase` examples embed an order that is `NOT
 * AVAILABLE` or `VOIDED` with a `null` `Memo`, so it is nullable and `#[Required]` too. The POST
 * body is `PurchaseOrderPostData`.
 *
 * @see docs/data.md
 */
final class PurchaseOrderData extends AbstractPurchaseOrderData implements WithResponse
{
    use HasResponse;

    /**
     * @param list<PurchaseOrderLineData> $Lines
     * @param null|list<SalePaymentLineData> $Prepayments
     */
    public function __construct(
        TaskStatus $Status,
        array $Lines,
        public float $TotalBeforeTax,
        public float $Tax,
        public float $Total,
        #[Required]
        #[Max(1024)]
        public ?string $Memo = null,
        #[Required]
        #[Uuid]
        public ?string $TaskID = null,
        #[Required]
        public ?bool $CombineAdditionalCharges = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Prepayments = null,
    ) {
        parent::__construct($Status, $Lines);
    }
}
