<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\BankTransfer;

use Hypervel\Data\Attributes\Validation\Uuid;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * The body of `bankTransfer` PUT: the Bank Transfer table with the `TaskID` PUT requires. The POST
 * body is `BankTransferPostData`.
 *
 * @see docs/data.md
 */
final class BankTransferPutData extends AbstractBankTransferData
{
    public function __construct(
        CompletionStatus $Status,
        string $FromAccount,
        string $ToAccount,
        float $FromAmount,
        float $ToAmount,
        string $Date,
        #[Uuid]
        public string $TaskID,
    ) {
        parent::__construct($Status, $FromAccount, $ToAccount, $FromAmount, $ToAmount, $Date);
    }
}
