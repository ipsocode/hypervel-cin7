<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\BankTransfer;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\CompletionStatus;

/**
 * Bank Transfer, the response of every `bankTransfer` action: the table with its `TaskID`. The
 * bodies of POST and PUT are `BankTransferPostData` and `BankTransferPutData`.
 *
 * @see docs/data.md
 */
final class BankTransferData extends AbstractBankTransferData implements WithResponse
{
    use HasResponse;

    public function __construct(
        CompletionStatus $Status,
        string $FromAccount,
        string $ToAccount,
        float $FromAmount,
        float $ToAmount,
        string $Date,
        #[Uuid]
        public ?string $TaskID = null,
    ) {
        parent::__construct($Status, $FromAccount, $ToAccount, $FromAmount, $ToAmount, $Date);
    }
}
