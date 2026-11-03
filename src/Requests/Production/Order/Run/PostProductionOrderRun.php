<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Run;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/order/run`, body is a `ProductionRunPostData`; the response is the production
 * order's runs.
 *
 * @extends WriteRequest<ProductionRunsData>
 */
final class PostProductionOrderRun extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = ProductionRunsData::class;

    public function resolveEndpoint(): string
    {
        return 'production/order/run';
    }
}
