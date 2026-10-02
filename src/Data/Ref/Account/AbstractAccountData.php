<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Account;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Attributes\Validation\RequiredIf;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\AccountClass;

/**
 * The fields of the Chart of Accounts table: the response of `ref/account` and the body of its
 * POST and PUT. Each is a final child that adds the fields of its verb.
 *
 * Every account needs its `Code`, `Name`, `Type` and `Status`, so each child passes them to this
 * constructor; the optional fields declared here are set through `from()`. A write body of a
 * `BANK` account also needs its `Bank` and `BankAccountNumber`. `Type` is a string: the
 * reference's examples send `CURRENT` and `EXPENSE`, outside its value list.
 *
 * @see docs/data.md
 */
abstract class AbstractAccountData extends Data
{
    public ?string $Description = null;

    public ?AccountClass $Class = null;

    public ?bool $ForPayments = null;

    #[RequiredIf('Type', 'BANK')]
    public ?string $Bank = null;

    #[RequiredIf('Type', 'BANK')]
    public ?string $BankAccountNumber = null;

    public function __construct(
        #[Max(50)]
        public string $Code,
        #[Max(256)]
        public string $Name,
        #[Max(50)]
        public string $Type,
        #[Max(50)]
        public string $Status,
    ) {
    }
}
