<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Product\MarkupPrices;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Product\MarkupPrices\MarkupPricesData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT product/markupprices`, body is a `MarkupPricesData`; the response is the product's markup
 * prices after the change.
 *
 * @extends WriteRequest<MarkupPricesData>
 */
final class PutProductMarkupPrices extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'product/markupprices';
    }

    public function createDtoFromResponse(Response $response): MarkupPricesData
    {
        return MarkupPricesData::from($response->json())->setResponse($response);
    }
}
