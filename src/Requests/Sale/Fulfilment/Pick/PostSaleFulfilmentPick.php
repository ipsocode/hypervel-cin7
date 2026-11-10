<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale\Fulfilment\Pick;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST sale/fulfilment/pick`, body is a `SaleFulfilmentPickPostData`; the response is the saved
 * pick.
 *
 * @extends WriteRequest<SaleFulfilmentPickData>
 */
final class PostSaleFulfilmentPick extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = SaleFulfilmentPickData::class;

    public function resolveEndpoint(): string
    {
        return 'sale/fulfilment/pick';
    }
}
