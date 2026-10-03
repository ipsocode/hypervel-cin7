<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\PriceTier;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\PriceTier\PriceTierData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET ref/priceTier`, the account's price tiers. It takes no parameters and is not paged: the
 * envelope is `{PriceTiers}` with no `Total`.
 *
 * @extends Cin7Request<list<PriceTierData>>
 */
final class GetPriceTier extends Cin7Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return 'ref/priceTier';
    }

    /**
     * @return list<PriceTierData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(PriceTierData::class, $response, $response->json('PriceTiers') ?? []);
    }
}
