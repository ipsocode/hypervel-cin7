<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\MoneyOperation;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Data\AttachmentLineData;

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
     * @param list<MoneyTaskLineData>|Optional $Lines
     * @param list<TransactionStockLineData>|Optional $Transactions
     * @param list<AttachmentLineData>|Optional $Attachments
     */
    public function __construct(
        public string|Optional $TaskID,
        public string|Optional $TaskType,
        public string|Optional $Status,
        public string|Optional $BankAccount,
        public float|Optional $CurrencyConversionRate,
        public string|Optional $SupplierCustomer,
        public string|Optional|null $SupplierID,
        public string|Optional|null $CustomerID,
        public string|Optional $Reference,
        public string|Optional $Date,
        public bool|Optional $TaxInclusive,
        public string|Optional|null $Note,
        #[DataCollectionOf(MoneyTaskLineData::class)]
        public array|Optional $Lines,
        #[DataCollectionOf(TransactionStockLineData::class)]
        public array|Optional $Transactions,
        #[DataCollectionOf(AttachmentLineData::class)]
        public array|Optional $Attachments,
    ) {
    }
}
