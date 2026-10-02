<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Fulfilment\SaleFulfilmentsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale/fulfilment`, body is a `SaleFulfilmentsData` with the `SaleID` of a sale whose order
 * is authorised; it starts a new fulfilment, and the response is the sale's fulfilments.
 *
 * @extends WriteRequest<SaleFulfilmentsData>
 */
final class PostSaleFulfilment extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'sale/fulfilment';
    }

    public function createDtoFromResponse(Response $response): SaleFulfilmentsData
    {
        return SaleFulfilmentsData::from($response->json())->setResponse($response);
    }
}
