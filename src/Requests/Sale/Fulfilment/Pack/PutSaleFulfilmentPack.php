<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment\Pack;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pack\SaleFulfilmentPackData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT sale/fulfilment/pack`, body is a `SaleFulfilmentPackData`, which replaces an unauthorized
 * pack; the response is the saved pack.
 *
 * @extends WriteRequest<SaleFulfilmentPackData>
 */
final class PutSaleFulfilmentPack extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = SaleFulfilmentPackData::class;

    public function resolveEndpoint(): string
    {
        return 'sale/fulfilment/pack';
    }
}
