<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Product;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Product\MarkupPrices\MarkupPricesData;
use Ipsocode\Cin7\Requests\Product\MarkupPrices\GetProductMarkupPrices;
use Ipsocode\Cin7\Requests\Product\MarkupPrices\PutProductMarkupPrices;

/**
 * `product/markupprices`, a product's markup prices for each price tier.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class MarkupPricesResource extends BaseResource
{
    /**
     * A product's markup prices, one line for each of the ten price tiers.
     */
    public function get(string $productId): Response
    {
        return $this->connector->send(new GetProductMarkupPrices($productId));
    }

    /**
     * Create, change or delete (with `MarkupType` `D`) a product's markup lines.
     *
     * @param array<string, mixed>|MarkupPricesData $body
     */
    public function put(array|MarkupPricesData $body): Response
    {
        return $this->connector->send(new PutProductMarkupPrices($body));
    }
}
