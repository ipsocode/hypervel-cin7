<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Run\Complete;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/order/run/complete`, body is a `ProductionRunCompletePutData`; the response is
 * the production order's runs.
 *
 * @extends WriteRequest<ProductionRunsData>
 */
final class PutProductionOrderRunComplete extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = ProductionRunsData::class;

    public function resolveEndpoint(): string
    {
        return 'production/order/run/complete';
    }
}
