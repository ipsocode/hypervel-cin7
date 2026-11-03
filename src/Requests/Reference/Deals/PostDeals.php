<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\Deals;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Reference\Deals\ProductDealData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST reference/deals`, body is a `ProductDealPostData`; the response is the saved deal, in `Deals`.
 *
 * @extends WriteRequest<ProductDealData>
 */
final class PostDeals extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = ProductDealData::class;

    protected ?string $dtoPath = 'Deals.0';

    public function resolveEndpoint(): string
    {
        return 'reference/deals';
    }
}
