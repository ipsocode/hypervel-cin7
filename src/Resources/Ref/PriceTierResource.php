<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Requests\Ref\PriceTier\GetPriceTier;

/**
 * `ref/priceTier`, the account's price tiers; they cannot be paged.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PriceTierResource extends BaseResource
{
    /**
     * The account's price tiers, by code and name.
     */
    public function get(): Response
    {
        return $this->connector->send(new GetPriceTier);
    }
}
