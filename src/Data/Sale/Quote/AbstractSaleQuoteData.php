<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Quote;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Data\Other\SalePaymentLineData;
use Ipsocode\Cin7\Data\Sale\SaleAdditionalChargeData;
use Ipsocode\Cin7\Enums\TaskStatus;

/**
 * The fields the Sale Quote Model and the Sale Quote table share: a sale's `Quote`, the response
 * of `sale/quote` and the body of its POST. Each is a final child that adds its own fields.
 *
 * Every quote needs its `Memo`, `Status` and `Lines`, so each child passes them to this
 * constructor; `Prepayments` and `AdditionalCharges` are set through `from()`.
 *
 * @see docs/data.md
 */
abstract class AbstractSaleQuoteData extends Data
{
    /**
     * @var null|list<SalePaymentLineData>
     */
    #[DataCollectionOf(SalePaymentLineData::class)]
    public ?array $Prepayments = null;

    /**
     * @var null|list<SaleAdditionalChargeData>
     */
    #[DataCollectionOf(SaleAdditionalChargeData::class)]
    public ?array $AdditionalCharges = null;

    /**
     * @param list<SaleQuoteLineData> $Lines
     */
    public function __construct(
        #[Max(1024)]
        public string $Memo,
        public TaskStatus $Status,
        #[DataCollectionOf(SaleQuoteLineData::class)]
        public array $Lines,
    ) {
    }
}
