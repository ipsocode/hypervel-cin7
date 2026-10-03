<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order;

use Hypervel\Data\Data;
use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\Order\ProductionOrdersData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/order`, body is a `ProductionOrderPostData`, with whether to
 * `RecalculateDates`; the response is the saved production order.
 *
 * @extends WriteRequest<ProductionOrdersData>
 */
final class PostProductionOrder extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @param array<string, mixed>|Data $body
     */
    public function __construct(
        array|Data $body = [],
        protected readonly ?bool $recalculateDates = null,
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
            'RecalculateDates' => $this->recalculateDates,
        ]);
    }

    public function createDtoFromResponse(Response $response): ProductionOrdersData
    {
        return ProductionOrdersData::from($response->json())->setResponse($response);
    }
}
