<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyOperation;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AttachmentLineData;
use Ipsocode\Cin7\Data\Attributes\DateTime;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Enums\MoneyTaskType;

/**
 * Money Task, the body of `moneyOperation` POST and PUT and the response of every
 * `moneyOperation` action.
 *
 * The reference's table names the counterparty `SupplierCustomerName`, but every example
 * returns `SupplierCustomer`; that is the wire key.
 *
 * @see docs/data.md
 */
final class MoneyTaskData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<MoneyTaskLineData> $Lines
     * @param null|list<TransactionStockLineData> $Transactions
     * @param null|list<AttachmentLineData> $Attachments
     */
    public function __construct(
        #[Uuid]
        public ?string $TaskID = null,
        public ?MoneyTaskType $TaskType = null,
        public ?CompletionStatus $Status = null,
        public ?string $BankAccount = null,
        public ?float $CurrencyConversionRate = null,
        public ?string $SupplierCustomer = null,
        #[Uuid]
        public ?string $SupplierID = null,
        #[Uuid]
        public ?string $CustomerID = null,
        public ?string $Reference = null,
        #[DateTime]
        public ?string $Date = null,
        public ?bool $TaxInclusive = null,
        public ?string $Note = null,
        #[DataCollectionOf(MoneyTaskLineData::class)]
        public ?array $Lines = null,
        #[DataCollectionOf(TransactionStockLineData::class)]
        public ?array $Transactions = null,
        #[DataCollectionOf(AttachmentLineData::class)]
        public ?array $Attachments = null,
    ) {
    }
}
