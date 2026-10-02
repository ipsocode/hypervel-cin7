<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyTask;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Attributes\DateTime;
use Ipsocode\Cin7\Data\Other\AttachmentLineData;
use Ipsocode\Cin7\Data\Other\TransactionStockLineData;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Enums\MoneyTaskType;

/**
 * The fields of the Money Task table: the response of every `moneyOperation` action and the body
 * of its POST and PUT. Each is a final child that adds its own fields.
 *
 * Every money task needs its `TaskType`, `Status`, `BankAccount` and `Date`, so each child passes
 * them to this constructor; the optional fields declared here are set through `from()`. The
 * table names the counterparty `SupplierCustomerName`, but every example returns
 * `SupplierCustomer`; that is the wire key.
 *
 * @see docs/data.md
 */
abstract class AbstractMoneyTaskData extends Data
{
    public ?float $CurrencyConversionRate = null;

    public ?string $SupplierCustomer = null;

    #[Uuid]
    public ?string $SupplierID = null;

    #[Uuid]
    public ?string $CustomerID = null;

    public ?string $Reference = null;

    public ?bool $TaxInclusive = null;

    public ?string $Note = null;

    /**
     * @var null|list<MoneyTaskLineData>
     */
    #[DataCollectionOf(MoneyTaskLineData::class)]
    public ?array $Lines = null;

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
        public MoneyTaskType $TaskType,
        public CompletionStatus $Status,
        public string $BankAccount,
        #[DateTime]
        public string $Date,
    ) {
    }
}
