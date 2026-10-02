<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\FinishedGoods\Order;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\FinishedGoodsStatus;

/**
 * Finished Goods Order, the body and response of `finishedGoods/order`: the order's `OrderLines`.
 * POST takes `DRAFT` or `AUTHORISED` as its `Status`.
 *
 * @see docs/data.md
 */
final class FinishedGoodsOrderData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<FinishedGoodsOrderLineData> $OrderLines
     */
    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        #[In(FinishedGoodsStatus::Draft, FinishedGoodsStatus::Authorised)]
        public ?FinishedGoodsStatus $Status = null,
        #[DataCollectionOf(FinishedGoodsOrderLineData::class)]
        public ?array $OrderLines = null,
    ) {
    }
}
