<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Purchase\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\Other\SalePaymentLineData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * Purchase Order Model, the `Order` of a purchase and an advanced purchase, and the Available
 * Fields for Purchase Order table, the response of `purchase/order`, which adds `TaskID` and
 * `CombineAdditionalCharges`. One name, so one class with the union of both: those two are
 * optional, as only the second table has them, and so are the `Prepayments`, which only the
 * first has. The POST body is `PurchaseOrderPostData`.
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
        string $Memo,
        TaskStatus $Status,
        array $Lines,
        public float $TotalBeforeTax,
        public float $Tax,
        public float $Total,
        #[Uuid]
        public ?string $TaskID = null,
        public ?bool $CombineAdditionalCharges = null,
        #[DataCollectionOf(SalePaymentLineData::class)]
        public ?array $Prepayments = null,
    ) {
        parent::__construct($Memo, $Status, $Lines);
    }
}
