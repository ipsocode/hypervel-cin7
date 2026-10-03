<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\FinishedGoods;

use Hypervel\Data\Attributes\Validation\In;
use Hypervel\Data\Attributes\Validation\RequiredIf;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\FinishedGoodsStatus;

/**
 * The body of `finishedGoods` POST: the table of POST fields, which requires `Status`,
 * `WIPAccount`, `Account`, `Quantity` and `CompletionDate`, a product by `ProductID` or
 * `ProductCode`, a location by `LocationID` or `Location`, and `WIPDate` once the `Status` is
 * `AUTHORISED`, `IN PROGRESS` or `COMPLETED`. `Status` is one of the first four values.
 * `ProductName` is in the example, not the table. The PUT body is `FinishedGoodsPutData`.
 *
 * @see docs/data.md
 */
final class FinishedGoodsPostData extends AbstractFinishedGoodsData
{
    public function __construct(
        #[In(FinishedGoodsStatus::Draft, FinishedGoodsStatus::Authorised, FinishedGoodsStatus::InProgress, FinishedGoodsStatus::Completed)]
        public FinishedGoodsStatus $Status,
        public string $WIPAccount,
        public string $Account,
        public float $Quantity,
        #[DateTime]
        public string $CompletionDate,
        #[RequiredWithout('ProductCode')]
        #[Uuid]
        public ?string $ProductID = null,
        #[RequiredWithout('ProductID')]
        public ?string $ProductCode = null,
        #[RequiredWithout('Location')]
        #[Uuid]
        public ?string $LocationID = null,
        #[RequiredWithout('LocationID')]
        public ?string $Location = null,
        #[RequiredIf('Status', FinishedGoodsStatus::Authorised, FinishedGoodsStatus::InProgress, FinishedGoodsStatus::Completed)]
        #[DateTime]
        public ?string $WIPDate = null,
    ) {
    }
}
