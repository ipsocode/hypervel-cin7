<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale\Fulfilment;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipPostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\Ship\SaleFulfilmentShipPutData;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship\GetSaleFulfilmentShip;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship\PostSaleFulfilmentShip;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\Ship\PutSaleFulfilmentShip;

/**
 * `sale/fulfilment/ship`, a fulfilment's shipment.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ShipResource extends BaseResource
{
    /**
     * A fulfilment's shipment.
     *
     * @return Response<SaleFulfilmentShipData>
     */
    public function get(string $taskId): Response
    {
        return $this->connector->send(new GetSaleFulfilmentShip($taskId));
    }

    /**
     * @param array<string, mixed>|SaleFulfilmentShipPostData $body
     * @return Response<SaleFulfilmentShipData>
     */
    public function post(array|SaleFulfilmentShipPostData $body): Response
    {
        return $this->connector->send(new PostSaleFulfilmentShip($body));
    }

    /**
     * @param array<string, mixed>|SaleFulfilmentShipPutData $body
     * @return Response<SaleFulfilmentShipData>
     */
    public function put(array|SaleFulfilmentShipPutData $body): Response
    {
        return $this->connector->send(new PutSaleFulfilmentShip($body));
    }
}
