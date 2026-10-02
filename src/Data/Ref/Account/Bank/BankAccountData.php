<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Account\Bank;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Bank Accounts, one entry of `BankAccountsList`: a bank account and the account in the chart of
 * accounts it is linked to.
 *
 * The reference types `InitialBalance` as String, but its example sends a number, so it takes
 * either.
 *
 * @see docs/data.md
 */
final class BankAccountData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        #[Uuid]
        public ?string $AccountID = null,
        #[Max(512)]
        public ?string $Bank = null,
        #[Max(256)]
        public ?string $AccountName = null,
        #[Max(256)]
        public ?string $AccountNumber = null,
        #[Max(50)]
        public ?string $AccountCode = null,
        #[Max(3)]
        public ?string $Currency = null,
        public ?float $StatementBalance = null,
        public ?float $BalanceInDear = null,
        public string|float|null $InitialBalance = null,
    ) {
    }
}
