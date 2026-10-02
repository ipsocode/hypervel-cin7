<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchaseData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST advanced-purchase`, body is an `AdvancedPurchasePostData` or an array; the response is the
 * saved purchase.
 *
 * @extends WriteRequest<AdvancedPurchaseData>
 */
final class PostAdvancedPurchase extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase';
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchaseData
    {
        return AdvancedPurchaseData::from($response->json())->setResponse($response);
    }
}
