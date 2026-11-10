<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = SaleData::class;

    public function resolveEndpoint(): string
    {
        return 'sale';
    }
}
