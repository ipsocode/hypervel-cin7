<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale`, body is a Sale; the response is the saved Sale.
 *
 * @extends WriteRequest<SaleData>
 */
final class PostSale extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'sale';
    }

    public function createDtoFromResponse(Response $response): SaleData
    {
        return SaleData::from($response->json())->setResponse($response);
    }
}
