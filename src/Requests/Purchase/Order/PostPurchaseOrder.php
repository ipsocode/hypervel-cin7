<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Order;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\Order\PurchaseOrderData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST purchase/order`, body is a `PurchaseOrderPostData`; the response is the saved order. Cin7
 * rejects it when the order is neither `DRAFT` nor `NOT AVAILABLE`.
 *
 * @extends WriteRequest<PurchaseOrderData>
 */
final class PostPurchaseOrder extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'purchase/order';
    }

    public function createDtoFromResponse(Response $response): PurchaseOrderData
    {
        return PurchaseOrderData::from($response->json())->setResponse($response);
    }
}
