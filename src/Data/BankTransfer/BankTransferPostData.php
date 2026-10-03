<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\BankTransfer;

/**
 * The body of `bankTransfer` POST: the Bank Transfer table without the `TaskID` Cin7 assigns. The
 * PUT body is `BankTransferPutData`.
 *
 * @see docs/data.md
 */
final class BankTransferPostData extends AbstractBankTransferData
{
}
