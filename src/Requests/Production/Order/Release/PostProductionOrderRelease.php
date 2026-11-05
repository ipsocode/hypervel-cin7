<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Release;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrdersData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/order/release`, body is a `ProductionOrderReleasePostData`; the response is the
 * production order.
 *
 * @extends WriteRequest<ProductionOrdersData>
 */
final class PostProductionOrderRelease extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = ProductionOrdersData::class;

    public function resolveEndpoint(): string
    {
        return 'production/order/release';
    }
}
