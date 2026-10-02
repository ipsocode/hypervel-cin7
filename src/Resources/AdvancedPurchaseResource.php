<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Resources\AdvancedPurchase\StockResource;

/**
 * `advanced-purchase`, an advanced purchase; `stock()` is the `advanced-purchase/stock`
 * sub-resource.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AdvancedPurchaseResource extends BaseResource
{
    /**
     * The `advanced-purchase/stock` resource, an advanced purchase's stock received.
     */
    public function stock(): StockResource
    {
        return new StockResource($this->connector);
    }
}
