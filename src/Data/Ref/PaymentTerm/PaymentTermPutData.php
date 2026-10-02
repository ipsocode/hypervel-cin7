<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\PaymentTerm;

use Hypervel\Data\Attributes\Validation\Uuid;

/**
 * The body of `ref/paymentterm` PUT: the table with the `ID` of the payment term to change, which
 * PUT requires. The POST body is `PaymentTermPostData`.
 *
 * @see docs/data.md
 */
final class PaymentTermPutData extends AbstractPaymentTermData
{
    public function __construct(
        string $Name,
        #[Uuid]
        public string $ID,
    ) {
        parent::__construct($Name);
    }
}
