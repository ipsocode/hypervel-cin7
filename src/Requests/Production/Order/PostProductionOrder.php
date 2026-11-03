<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order;

use Hypervel\Data\Data;
use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = ProductionOrdersData::class;

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
}
