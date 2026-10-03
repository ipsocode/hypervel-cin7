<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Sale;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Sale\Fulfilment\SaleFulfilmentsData;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\DeleteSaleFulfilment;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\GetSaleFulfilment;
use Ipsocode\Cin7\Requests\Sale\Fulfilment\PostSaleFulfilment;
use Ipsocode\Cin7\Resources\Sale\Fulfilment\PackResource;
use Ipsocode\Cin7\Resources\Sale\Fulfilment\PickResource;
use Ipsocode\Cin7\Resources\Sale\Fulfilment\ShipResource;

/**
 * `sale/fulfilment`, a sale's fulfilments, and the pick, pack and ship of each.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class FulfilmentResource extends BaseResource
{
    /**
     * A sale's fulfilments.
     *
     * @param null|bool $includeProductInfo add the products the lines use
     */
    public function get(string $saleId, ?bool $includeProductInfo = null): Response
    {
        return $this->connector->send(new GetSaleFulfilment($saleId, $includeProductInfo));
    }

    /**
     * Start a new fulfilment of a sale whose order is authorised.
     *
     * @param array<string, mixed>|SaleFulfilmentsData $body
     */
    public function post(array|SaleFulfilmentsData $body): Response
    {
        return $this->connector->send(new PostSaleFulfilment($body));
    }

    /**
     * Void the fulfilment (`void: true`), or undo a void (`false`, the default Cin7 applies).
     *
     * @param null|bool $void void (true) or undo a void (false)
     */
    public function delete(string $taskId, ?bool $void = null): Response
    {
        return $this->connector->send(new DeleteSaleFulfilment($taskId, $void));
    }

    public function pick(): PickResource
    {
        return new PickResource($this->connector);
    }

    public function pack(): PackResource
    {
        return new PackResource($this->connector);
    }

    public function ship(): ShipResource
    {
        return new ShipResource($this->connector);
    }
}
