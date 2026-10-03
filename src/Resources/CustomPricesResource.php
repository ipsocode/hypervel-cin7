<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\CustomPrices\CustomPricesData;
use Ipsocode\Cin7\Requests\CustomPrices\DeleteCustomPrices;
use Ipsocode\Cin7\Requests\CustomPrices\PostCustomPrices;
use Ipsocode\Cin7\Requests\CustomPrices\PutCustomPrices;

/**
 * `custom-prices`, the prices set for one customer. It has no GET: a product's are in its
 * `CustomPrices`, and a customer's in its `ProductPrices`. A POST or PUT answers `{Errors}`, a DELETE
 * `{Success}`, both left to `json()`.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class CustomPricesResource extends BaseResource
{
    /**
     * Create customer specific prices for products.
     *
     * @param array<string, mixed>|CustomPricesData $body
     */
    public function post(array|CustomPricesData $body): Response
    {
        return $this->connector->send(new PostCustomPrices($body));
    }

    /**
     * Change customer specific prices for products.
     *
     * @param array<string, mixed>|CustomPricesData $body
     */
    public function put(array|CustomPricesData $body): Response
    {
        return $this->connector->send(new PutCustomPrices($body));
    }

    /**
     * Delete a customer's custom price for a product.
     */
    public function delete(string $productId, string $customerId): Response
    {
        return $this->connector->send(new DeleteCustomPrices($productId, $customerId));
    }
}
