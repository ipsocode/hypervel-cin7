<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\PurchaseData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST purchase`, body is a `PurchasePostData` or an array; the response is the saved purchase.
 *
 * @extends WriteRequest<PurchaseData>
 */
final class PostPurchase extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'purchase';
    }

    public function createDtoFromResponse(Response $response): PurchaseData
    {
        return PurchaseData::from($response->json())->setResponse($response);
    }
}
