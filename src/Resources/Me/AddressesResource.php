<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Me;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Me\Addresses\MeAddressPostData;
use Ipsocode\Cin7\Data\Me\Addresses\MeAddressPutData;
use Ipsocode\Cin7\Enums\AddressType;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Me\Addresses\DeleteMeAddresses;
use Ipsocode\Cin7\Requests\Me\Addresses\GetMeAddresses;
use Ipsocode\Cin7\Requests\Me\Addresses\PostMeAddresses;
use Ipsocode\Cin7\Requests\Me\Addresses\PutMeAddresses;

/**
 * `me/addresses`, the company's addresses.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class AddressesResource extends BaseResource
{
    /**
     * One page of the company's addresses; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the address with this ID
     * @param null|AddressType $type only addresses of this type
     * @param null|bool $defaultForType only addresses that are the default for their type
     * @param null|string $country only addresses in this country
     * @param null|string $stateProvince only addresses in this state or province
     * @param null|string $citySuburb only addresses in this city or suburb
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?AddressType $type = null,
        ?bool $defaultForType = null,
        ?string $country = null,
        ?string $stateProvince = null,
        ?string $citySuburb = null,
    ): Response {
        return $this->connector->send(new GetMeAddresses(
            $page,
            $limit,
            $id,
            $type,
            $defaultForType,
            $country,
            $stateProvince,
            $citySuburb,
        ));
    }

    /**
     * Every page of the company's addresses, fetched as they are walked; call `startPage()` on the
     * paginator to begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the address with this ID
     * @param null|AddressType $type only addresses of this type
     * @param null|bool $defaultForType only addresses that are the default for their type
     * @param null|string $country only addresses in this country
     * @param null|string $stateProvince only addresses in this state or province
     * @param null|string $citySuburb only addresses in this city or suburb
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?AddressType $type = null,
        ?bool $defaultForType = null,
        ?string $country = null,
        ?string $stateProvince = null,
        ?string $citySuburb = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetMeAddresses(
            null,
            $limit,
            $id,
            $type,
            $defaultForType,
            $country,
            $stateProvince,
            $citySuburb,
        ));
    }

    /**
     * @param array<string, mixed>|MeAddressPostData $body
     */
    public function post(array|MeAddressPostData $body): Response
    {
        return $this->connector->send(new PostMeAddresses($body));
    }

    /**
     * @param array<string, mixed>|MeAddressPutData $body
     */
    public function put(array|MeAddressPutData $body): Response
    {
        return $this->connector->send(new PutMeAddresses($body));
    }

    /**
     * Delete one of the company's addresses.
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteMeAddresses($id));
    }
}
