<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTransfer\Order;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = StockTransferOrderData::class;

    public function resolveEndpoint(): string
    {
        return 'stockTransfer/order';
    }
}
