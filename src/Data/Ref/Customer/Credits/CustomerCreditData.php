<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Customer\Credits;

use Hypervel\Data\Data;
use Hypervel\Data\Optional;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Customer Credits, one entry of `CustomerCredits`.
 *
 * @see docs/data.md
 */
final class CustomerCreditData extends Data implements WithResponse
{
    use HasResponse;

    public function __construct(
        public string|Optional $CreditID,
        public string|Optional $CustomerID,
        public string|Optional $CustomerName,
        public string|Optional $Account,
        public float|Optional $Amount,
        public float|Optional $RemainingAmount,
        public string|Optional $Currency,
        public float|Optional $ConvRate,
        public string|Optional $Date,
        public string|Optional $Description,
    ) {
    }
}
