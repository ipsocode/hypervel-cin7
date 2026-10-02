<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\PaymentTerm;

use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Payment Term, one entry of `PaymentTermList` in every `ref/paymentterm` response: the table with
 * its `ID`. The bodies of POST and PUT are `PaymentTermPostData` and `PaymentTermPutData`.
 *
 * @see docs/data.md
 */
final class PaymentTermData extends AbstractPaymentTermData implements WithResponse
{
    use HasResponse;

    public function __construct(
        string $Name,
        #[Uuid]
        public ?string $ID = null,
    ) {
        parent::__construct($Name);
    }
}
