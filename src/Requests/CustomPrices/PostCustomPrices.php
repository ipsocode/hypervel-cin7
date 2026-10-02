<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\CustomPrices;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST custom-prices`, body is a `CustomPricesData`; the response `{Errors}` is left to `json()`.
 *
 * @extends WriteRequest<null>
 */
final class PostCustomPrices extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'custom-prices';
    }
}
