<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Reference;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Reference\ShipZones\ShippingZonePostData;
use Ipsocode\Cin7\Data\Reference\ShipZones\ShippingZonePutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Reference\ShipZones\DeleteShipZones;
use Ipsocode\Cin7\Requests\Reference\ShipZones\GetShipZones;
use Ipsocode\Cin7\Requests\Reference\ShipZones\PostShipZones;
use Ipsocode\Cin7\Requests\Reference\ShipZones\PutShipZones;

/**
 * `reference/shipZones`, the shipping zones that set a customer's shipping fees.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ShipZonesResource extends BaseResource
{
    /**
     * One page of ship zones; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the ship zone with this ID
     * @param null|string $search only ship zones with this text in their name
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $search = null,
    ): Response {
        return $this->connector->send(new GetShipZones($page, $limit, $id, $search));
    }

    /**
     * Every page of ship zones, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the ship zone with this ID
     * @param null|string $search only ship zones with this text in their name
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $search = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetShipZones(null, $limit, $id, $search));
    }

    /**
     * @param array<string, mixed>|ShippingZonePostData $body
     */
    public function post(array|ShippingZonePostData $body): Response
    {
        return $this->connector->send(new PostShipZones($body));
    }

    /**
     * @param array<string, mixed>|ShippingZonePutData $body
     */
    public function put(array|ShippingZonePutData $body): Response
    {
        return $this->connector->send(new PutShipZones($body));
    }

    /**
     * Delete a ship zone.
     */
    public function delete(string $shipZoneId): Response
    {
        return $this->connector->send(new DeleteShipZones($shipZoneId));
    }
}
