<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrdersData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET production/order?ProductionOrderID`, a production order, answered under `ProductionOrders`.
 *
 * @extends Cin7Request<ProductionOrdersData>
 */
final class GetProductionOrder extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $productionOrderId,
        protected readonly ?bool $returnAttachmentsContent = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'production/order';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ProductionOrderID' => $this->productionOrderId,
            'ReturnAttachmentsContent' => $this->returnAttachmentsContent,
        ]);
    }

    public function createDtoFromResponse(Response $response): ProductionOrdersData
    {
        return ProductionOrdersData::from($response->json())->setResponse($response);
    }
}
