<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\StockTransfer;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\RequiredIf;
use Hypervel\Data\Attributes\Validation\RequiredWithout;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CostDistributionType;
use Ipsocode\Cin7\Enums\StockTransferStatus;

/**
 * The fields of the Stock Transfer table: the response of every `stockTransfer` action and the
 * body of its POST and PUT. Each is a final child that adds its own fields.
 *
 * Every stock transfer needs its `Status`, `CompletionDate` and `Lines`, so each child passes them
 * to this constructor; the optional fields declared here are set through `from()`. A write body
 * also needs the location it moves stock from, `From` or `FromLocation`, and the one it moves it
 * to, `To` or `ToLocation`, and `InTransitAccount` and `DepartureDate` when `Status` is
 * `IN TRANSIT`, or it fails validation before it is sent. The examples send `ManualJournals`,
 * always empty and in no table, so it is kept as a list of whatever the reference puts in it.
 *
 * @see docs/data.md
 */
abstract class AbstractStockTransferData extends Data
{
    #[RequiredWithout('FromLocation')]
    #[Uuid]
    public ?string $From = null;

    #[RequiredWithout('From')]
    public ?string $FromLocation = null;

    #[RequiredWithout('ToLocation')]
    #[Uuid]
    public ?string $To = null;

    #[RequiredWithout('To')]
    public ?string $ToLocation = null;

    public ?CostDistributionType $CostDistributionType = null;

    #[RequiredIf('Status', StockTransferStatus::InTransit)]
    public ?string $InTransitAccount = null;

    #[RequiredIf('Status', StockTransferStatus::InTransit)]
    #[DateTime]
    public ?string $DepartureDate = null;

    #[DateTime]
    public ?string $RequiredByDate = null;

    public ?string $Reference = null;

    public ?bool $SkipOrder = null;

    /**
     * @var null|list<mixed>
     */
    public ?array $ManualJournals = null;

    /**
     * @param list<StockTransferLineData> $Lines
     */
    public function __construct(
        public StockTransferStatus $Status,
        #[DateTime]
        public string $CompletionDate,
        #[DataCollectionOf(StockTransferLineData::class)]
        public array $Lines,
    ) {
    }
}
