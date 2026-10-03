<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTransfer\Order;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\StockTransfer\Order\StockTransferOrderData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST stockTransfer/order`, body is a `StockTransferOrderPostData`; the response is the saved stock transfer order.
 *
 * @extends WriteRequest<StockTransferOrderData>
 */
final class PostStockTransferOrder extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'stockTransfer/order';
    }

    public function createDtoFromResponse(Response $response): StockTransferOrderData
    {
        return StockTransferOrderData::from($response->json())->setResponse($response);
    }
}
