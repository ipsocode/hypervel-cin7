<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\PaymentTerm;

use Hypervel\Data\Attributes\Validation\Max;
use Hypervel\Data\Data;
use Ipsocode\Cin7\Enums\PaymentTermMethod;

/**
 * The fields of the Payment Term table: the response of `ref/paymentterm` and the body of its POST
 * and PUT. Each is a final child that adds its `ID`, or none.
 *
 * Every payment term needs its `Name`, so each child passes it to this constructor. Cin7 defaults
 * `IsActive` to `true` and `IsDefault` to `false` on POST.
 *
 * @see docs/data.md
 */
abstract class AbstractPaymentTermData extends Data
{
    public ?int $Duration = null;

    public ?PaymentTermMethod $Method = null;

    public ?bool $IsActive = null;

    public ?bool $IsDefault = null;

    public function __construct(
        #[Max(256)]
        public string $Name,
    ) {
    }
}
