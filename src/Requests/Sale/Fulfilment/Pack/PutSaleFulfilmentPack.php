<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pack\SaleFulfilmentPackData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT sale/fulfilment/pack`, body is a `SaleFulfilmentPackData`, which replaces an unauthorised
 * pack; the response is the saved pack.
 *
 * @extends WriteRequest<SaleFulfilmentPackData>
 */
final class PutSaleFulfilmentPack extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'sale/fulfilment/pack';
    }

    public function createDtoFromResponse(Response $response): SaleFulfilmentPackData
    {
        return SaleFulfilmentPackData::from($response->json())->setResponse($response);
    }
}
