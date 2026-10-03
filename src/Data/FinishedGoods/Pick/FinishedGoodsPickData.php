<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\FinishedGoods\Pick;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\FinishedGoodsStatus;

/**
 * Finished Goods Pick, the body and response of `finishedGoods/pick`: the pick's `PickLines`, with
 * the accounts and dates it is completed with. POST takes `AUTHORISED`, `IN PROGRESS` or
 * `COMPLETED` as its `Status`; the `CompletionDate` is required.
 *
 * @see docs/data.md
 */
final class FinishedGoodsPickData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<FinishedGoodsPickLineData> $PickLines
     */
    public function __construct(
        #[DateTime]
        public string $CompletionDate,
        #[Uuid]
        public ?string $TaskID = null,
        #[In(FinishedGoodsStatus::Authorised, FinishedGoodsStatus::InProgress, FinishedGoodsStatus::Completed)]
        public ?FinishedGoodsStatus $Status = null,
        public ?string $WIPAccount = null,
        #[DateTime]
        public ?string $WIPDate = null,
        public ?string $Account = null,
        #[DataCollectionOf(FinishedGoodsPickLineData::class)]
        public ?array $PickLines = null,
    ) {
    }
}
