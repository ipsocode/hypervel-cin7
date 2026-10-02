<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use DateTimeInterface;
use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Supplier\SupplierPostData;
use Ipsocode\Cin7\Data\Supplier\SupplierPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Supplier\GetSupplier;
use Ipsocode\Cin7\Requests\Supplier\PostSupplier;
use Ipsocode\Cin7\Requests\Supplier\PutSupplier;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class SupplierResource extends BaseResource
{
    /**
     * One page of suppliers; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the supplier with this ID
     * @param null|string $name only suppliers whose name starts with this
     * @param null|DateTimeInterface|string $modifiedSince only suppliers modified since this time
     * @param null|bool $includeDeprecated include deprecated suppliers too
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        DateTimeInterface|string|null $modifiedSince = null,
        ?bool $includeDeprecated = null,
    ): Response {
        return $this->connector->send(new GetSupplier(
            $page,
            $limit,
            $id,
            $name,
            $modifiedSince,
            $includeDeprecated,
        ));
    }

    /**
     * Every page of suppliers, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the supplier with this ID
     * @param null|string $name only suppliers whose name starts with this
     * @param null|DateTimeInterface|string $modifiedSince only suppliers modified since this time
     * @param null|bool $includeDeprecated include deprecated suppliers too
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        DateTimeInterface|string|null $modifiedSince = null,
        ?bool $includeDeprecated = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetSupplier(
            null,
            $limit,
            $id,
            $name,
            $modifiedSince,
            $includeDeprecated,
        ));
    }

    /**
     * @param array<string, mixed>|SupplierPostData $body
     */
    public function post(array|SupplierPostData $body): Response
    {
        return $this->connector->send(new PostSupplier($body));
    }

    /**
     * @param array<string, mixed>|SupplierPutData $body
     */
    public function put(array|SupplierPutData $body): Response
    {
        return $this->connector->send(new PutSupplier($body));
    }
}
