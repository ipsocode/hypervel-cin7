<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Account;

/**
 * The body of `ref/account` PUT: the Chart of Accounts table without its read-only fields, and
 * without `SystemAccount` and `SystemAccountCode`, which are read-only for PUT. `Code` names the
 * account to change. The POST body is `AccountPostData`.
 *
 * @see docs/data.md
 */
final class AccountPutData extends AbstractAccountData
{
}
