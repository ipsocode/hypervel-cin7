<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoods\Order;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = FinishedGoodsOrderData::class;

    public function __construct(
        protected readonly string $taskId,
    ) {
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
}
