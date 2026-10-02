<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Account;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;
use Ipsocode\Cin7\Enums\SystemAccount;
use Ipsocode\Cin7\Enums\SystemAccountCode;

/**
 * Chart of Accounts, one entry of `AccountsList` in every `ref/account` response: the table with
 * its read-only `DisplayName`, `OldCode`, `BankAccountId` and `Currency`. The bodies of POST and
 * PUT are `AccountPostData` and `AccountPutData`.
 *
 * @see docs/data.md
 */
final class AccountData extends AbstractAccountData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Code,
        string $Name,
        string $Type,
        string $Status,
        public ?SystemAccount $SystemAccount = null,
        public ?SystemAccountCode $SystemAccountCode = null,
        public ?string $DisplayName = null,
        public ?string $OldCode = null,
        #[Uuid]
        public ?string $BankAccountId = null,
        public ?string $Currency = null,
    ) {
        parent::__construct($Code, $Name, $Type, $Status);
    }
}
