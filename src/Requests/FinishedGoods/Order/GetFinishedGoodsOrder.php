<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoods\Order;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\FinishedGoods\Order\FinishedGoodsOrderData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET finishedGoods/order?TaskID`, a finished goods task's order.
 *
 * @extends Cin7Request<FinishedGoodsOrderData>
 */
final class GetFinishedGoodsOrder extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'finishedGoods/order';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
        ]);
    }

    public function createDtoFromResponse(Response $response): FinishedGoodsOrderData
    {
        return FinishedGoodsOrderData::from($response->json())->setResponse($response);
    }
}
