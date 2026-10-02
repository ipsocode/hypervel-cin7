<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Tax\GetTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PostTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PutTax;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class TaxResource extends BaseResource
{
    /**
     * One page of tax rules; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the tax rule with this ID
     * @param null|string $name only tax rules whose name starts with this
     * @param null|bool $isActive only active tax rules
     * @param null|bool $isTaxForSale only tax rules for sales
     * @param null|bool $isTaxForPurchase only tax rules for purchases
     * @param null|string $account only tax rules linked to this account code
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?bool $isActive = null,
        ?bool $isTaxForSale = null,
        ?bool $isTaxForPurchase = null,
        ?string $account = null,
    ): Response {
        return $this->connector->send(new GetTax(
            $page,
            $limit,
            $id,
            $name,
            $isActive,
            $isTaxForSale,
            $isTaxForPurchase,
            $account,
        ));
    }

    /**
     * Every page of tax rules, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the tax rule with this ID
     * @param null|string $name only tax rules whose name starts with this
     * @param null|bool $isActive only active tax rules
     * @param null|bool $isTaxForSale only tax rules for sales
     * @param null|bool $isTaxForPurchase only tax rules for purchases
     * @param null|string $account only tax rules linked to this account code
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?bool $isActive = null,
        ?bool $isTaxForSale = null,
        ?bool $isTaxForPurchase = null,
        ?string $account = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetTax(
            null,
            $limit,
            $id,
            $name,
            $isActive,
            $isTaxForSale,
            $isTaxForPurchase,
            $account,
        ));
    }

    /**
     * @param array<string, mixed>|TaxData $body
     */
    public function post(array|TaxData $body): Response
    {
        return $this->connector->send(new PostTax($body));
    }

    /**
     * @param array<string, mixed>|TaxData $body
     */
    public function put(array|TaxData $body): Response
    {
        return $this->connector->send(new PutTax($body));
    }
}
