<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\BankTransfer;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Data\Other\TransactionStockLineData;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * The fields of the Bank Transfer table: the response of every `bankTransfer` action and the body
 * of its POST and PUT. Each is a final child that adds its `TaskID`, or none.
 *
 * Every bank transfer needs its `Status`, `FromAccount`, `ToAccount`, `FromAmount`, `ToAmount` and
 * `Date`, so each child passes them to this constructor; the optional fields declared here are
 * set through `from()`. The table's heading says "Money Task List", copied from the list. Cin7
 * works out `CurrencyConversionRate` itself, so it is read-only, and no write example sends it.
 *
 * @see docs/data.md
 */
abstract class AbstractBankTransferData extends Data
{
    public ?float $CurrencyConversionRate = null;

    public ?string $Reference = null;

    public ?string $Note = null;

    /**
     * @var null|list<TransactionStockLineData>
     */
    #[DataCollectionOf(TransactionStockLineData::class)]
    public ?array $Transactions = null;

    /**
     * @var null|list<AttachmentLineData>
     */
    #[DataCollectionOf(AttachmentLineData::class)]
    public ?array $Attachments = null;

    public function __construct(
        public CompletionStatus $Status,
        public string $FromAccount,
        public string $ToAccount,
        public float $FromAmount,
        public float $ToAmount,
        #[DateTime]
        public string $Date,
    ) {
    }
}
