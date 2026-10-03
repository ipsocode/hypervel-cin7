<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Run;

use Hypervel\Data\Data;
use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/order/run?ProductionOrderID&IncreaseOrderQuantity`, body is a
 * `ProductionRunData`; the response is the saved run.
 *
 * @extends WriteRequest<ProductionRunData>
 */
final class PutProductionOrderRun extends WriteRequest
{
    protected Method $method = Method::PUT;

    /**
     * @param array<string, mixed>|Data $body
     */
    public function __construct(
        array|Data $body,
        protected readonly string $productionOrderId,
        protected readonly bool $increaseOrderQuantity,
    ) {
        parent::__construct($body);
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
            'IncreaseOrderQuantity' => $this->increaseOrderQuantity,
        ]);
    }

    public function createDtoFromResponse(Response $response): ProductionRunData
    {
        return ProductionRunData::from($response->json())->setResponse($response);
    }
}
