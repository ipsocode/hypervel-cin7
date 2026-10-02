<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Account;

use Ipsocode\Cin7\Enums\SystemAccount;
use Ipsocode\Cin7\Enums\SystemAccountCode;

/**
 * The body of `ref/account` POST: the Chart of Accounts table without its read-only fields, with
 * the `SystemAccount` and `SystemAccountCode` a PUT cannot change. Either names the system
 * account; both, if given, must name the same one. The PUT body is `AccountPutData`.
 *
 * @see docs/data.md
 */
final class AccountPostData extends AbstractAccountData
{
    public function __construct(
        string $Code,
        string $Name,
        string $Type,
        string $Status,
        public ?SystemAccount $SystemAccount = null,
        public ?SystemAccountCode $SystemAccountCode = null,
    ) {
        parent::__construct($Code, $Name, $Type, $Status);
    }
}
