<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTransfer\Order;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\StockTransfer\Order\StockTransferOrderData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET stockTransfer/order?TaskID`, a stock transfer's order.
 *
 * @extends Cin7Request<StockTransferOrderData>
 */
final class GetStockTransferOrder extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'stockTransfer/order';
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

    public function createDtoFromResponse(Response $response): StockTransferOrderData
    {
        return StockTransferOrderData::from($response->json())->setResponse($response);
    }
}
