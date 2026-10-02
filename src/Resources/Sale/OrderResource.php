<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Order\SaleOrderData;
use Ipsocode\Cin7\Requests\Sale\Order\GetSaleOrder;
use Ipsocode\Cin7\Requests\Sale\Order\PostSaleOrder;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class OrderResource extends BaseResource
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function get(string $saleId, array $parameters = []): Response
    {
        return $this->connector->send(new GetSaleOrder($saleId, $parameters));
    }

    /**
     * @param array<string, mixed>|SaleOrderData $body
     */
    public function post(array|SaleOrderData $body): Response
    {
        return $this->connector->send(new PostSaleOrder($body));
    }
}
