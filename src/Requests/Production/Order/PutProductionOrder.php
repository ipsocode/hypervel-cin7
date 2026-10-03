<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order;

use Hypervel\Data\Data;
use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrdersData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/order`, body is a `ProductionOrderPutData`, with whether to
 * `AllowRecalculateDates` and `AllowRecalculateCyclesAndQuantities`; the response is the saved
 * production order.
 *
 * @extends WriteRequest<ProductionOrdersData>
 */
final class PutProductionOrder extends WriteRequest
{
    protected Method $method = Method::PUT;

    /**
     * @param array<string, mixed>|Data $body
     */
    public function __construct(
        array|Data $body = [],
        protected readonly ?bool $allowRecalculateDates = null,
        protected readonly ?bool $allowRecalculateCyclesAndQuantities = null,
    ) {
        parent::__construct($body);
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
            'AllowRecalculateDates' => $this->allowRecalculateDates,
            'AllowRecalculateCyclesAndQuantities' => $this->allowRecalculateCyclesAndQuantities,
        ]);
    }

    public function createDtoFromResponse(Response $response): ProductionOrdersData
    {
        return ProductionOrdersData::from($response->json())->setResponse($response);
    }
}
