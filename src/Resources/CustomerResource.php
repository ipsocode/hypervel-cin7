<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use DateTimeInterface;
use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Customer\CustomerPostData;
use Ipsocode\Cin7\Data\Customer\CustomerPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\Customer\PutCustomer;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class CustomerResource extends BaseResource
{
    /**
     * One page of customers; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the customer with this ID
     * @param null|string $name only customers whose name starts with this
     * @param null|DateTimeInterface|string $modifiedSince only customers modified since this time
     * @param null|bool $includeDeprecated include deprecated customers too
     * @param null|bool $includeProductPrices include each customer's product prices
     * @param null|string $contactFilter only customers with a contact whose name starts with, or
     *                                   whose email is, this
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        DateTimeInterface|string|null $modifiedSince = null,
        ?bool $includeDeprecated = null,
        ?bool $includeProductPrices = null,
        ?string $contactFilter = null,
    ): Response {
        return $this->connector->send(new GetCustomer(
            $page,
            $limit,
            $id,
            $name,
            $modifiedSince,
            $includeDeprecated,
            $includeProductPrices,
            $contactFilter,
        ));
    }

    /**
     * Every page of customers, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the customer with this ID
     * @param null|string $name only customers whose name starts with this
     * @param null|DateTimeInterface|string $modifiedSince only customers modified since this time
     * @param null|bool $includeDeprecated include deprecated customers too
     * @param null|bool $includeProductPrices include each customer's product prices
     * @param null|string $contactFilter only customers with a contact whose name starts with, or
     *                                   whose email is, this
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        DateTimeInterface|string|null $modifiedSince = null,
        ?bool $includeDeprecated = null,
        ?bool $includeProductPrices = null,
        ?string $contactFilter = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetCustomer(
            null,
            $limit,
            $id,
            $name,
            $modifiedSince,
            $includeDeprecated,
            $includeProductPrices,
            $contactFilter,
        ));
    }

    /**
     * @param array<string, mixed>|CustomerPostData $body
     */
    public function post(array|CustomerPostData $body): Response
    {
        return $this->connector->send(new PostCustomer($body));
    }

    /**
     * @param array<string, mixed>|CustomerPutData $body
     */
    public function put(array|CustomerPutData $body): Response
    {
        return $this->connector->send(new PutCustomer($body));
    }
}
