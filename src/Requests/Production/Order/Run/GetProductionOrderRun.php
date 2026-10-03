<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Run;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET production/order/run?ProductionOrderID`, the runs of a production order.
 *
 * @extends Cin7Request<ProductionRunsData>
 */
final class GetProductionOrderRun extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $productionOrderId,
        protected readonly ?bool $includeAttachmentContent = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'production/order/run';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ProductionOrderID' => $this->productionOrderId,
            'IncludeAttachmentContent' => $this->includeAttachmentContent,
        ]);
    }

    public function createDtoFromResponse(Response $response): ProductionRunsData
    {
        return ProductionRunsData::from($response->json())->setResponse($response);
    }
}
