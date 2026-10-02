<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Ref\Customer\Credits;

use Hypervel\Data\Data;
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
        public ?string $CreditID = null,
        public ?string $CustomerID = null,
        public ?string $CustomerName = null,
        public ?string $Account = null,
        public ?float $Amount = null,
        public ?float $RemainingAmount = null,
        public ?string $Currency = null,
        public ?float $ConvRate = null,
        public ?string $Date = null,
        public ?string $Description = null,
    ) {
    }
}
