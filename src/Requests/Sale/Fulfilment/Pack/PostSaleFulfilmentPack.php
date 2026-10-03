<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pack\SaleFulfilmentPackData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale/fulfilment/pack`, body is a `SaleFulfilmentPackPostData`, which creates a pack or
 * adds lines to one; the response is the saved pack.
 *
 * @extends WriteRequest<SaleFulfilmentPackData>
 */
final class PostSaleFulfilmentPack extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'sale/fulfilment/pack';
    }

    public function createDtoFromResponse(Response $response): SaleFulfilmentPackData
    {
        return SaleFulfilmentPackData::from($response->json())->setResponse($response);
    }
}
