<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Data\Sale\Fulfilment;

use Hypervel\Data\Attributes\DataCollectionOf;
use Hypervel\Data\Attributes\Validation\Uuid;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Traits\Responses\HasResponse;

/**
 * Sale Fulfilment, the `{SaleID, Fulfilments}` envelope every `sale/fulfilment` action answers
 * with, and the body of its POST, which needs only the `SaleID` to start a new fulfilment.
 *
 * @see docs/data.md
 */
final class SaleFulfilmentsData extends Data implements WithResponse
{
    use HasResponse;

    /**
     * @param null|list<SaleFulfilmentData> $Fulfilments
     */
    public function __construct(
        #[Uuid]
        public string $SaleID,
        #[DataCollectionOf(SaleFulfilmentData::class)]
        public ?array $Fulfilments = null,
    ) {
    }
}
