<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Carrier\CarrierPostData;
use Ipsocode\Cin7\Data\Ref\Carrier\CarrierPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Carrier\DeleteCarrier;
use Ipsocode\Cin7\Requests\Ref\Carrier\GetCarrier;
use Ipsocode\Cin7\Requests\Ref\Carrier\PostCarrier;
use Ipsocode\Cin7\Requests\Ref\Carrier\PutCarrier;

/**
 * `ref/carrier`, the carriers.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class CarrierResource extends BaseResource
{
    /**
     * One page of carriers; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $carrierId only the carrier with this CarrierID
     * @param null|string $description only carriers whose description starts with this
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $carrierId = null,
        ?string $description = null,
    ): Response {
        return $this->connector->send(new GetCarrier(
            $page,
            $limit,
            $carrierId,
            $description,
        ));
    }

    /**
     * Every page of carriers, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $carrierId only the carrier with this CarrierID
     * @param null|string $description only carriers whose description starts with this
     */
    public function paginate(
        ?int $limit = null,
        ?string $carrierId = null,
        ?string $description = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetCarrier(
            null,
            $limit,
            $carrierId,
            $description,
        ));
    }

    /**
     * @param array<string, mixed>|CarrierPostData $body
     */
    public function post(array|CarrierPostData $body): Response
    {
        return $this->connector->send(new PostCarrier($body));
    }

    /**
     * @param array<string, mixed>|CarrierPutData $body
     */
    public function put(array|CarrierPutData $body): Response
    {
        return $this->connector->send(new PutCarrier($body));
    }

    /**
     * Delete a carrier.
     *
     * @param string $id the ID of the carrier
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteCarrier($id));
    }
}
