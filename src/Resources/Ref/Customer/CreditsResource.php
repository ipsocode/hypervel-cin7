<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref\Customer;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\Customer\Credits\GetCustomerCredits;

/**
 * @extends BaseResource<Cin7Connector>
 */
final class CreditsResource extends BaseResource
{
    /**
     * One page of customer credits; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $customerId only this customer's credits
     * @param null|bool $showUsedCredits include the credits already used
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $customerId = null,
        ?bool $showUsedCredits = null,
    ): Response {
        return $this->connector->send(new GetCustomerCredits(
            $page,
            $limit,
            $customerId,
            $showUsedCredits,
        ));
    }

    /**
     * Every page of customer credits, fetched as they are walked; call `startPage()` on the
     * paginator to begin later. The envelope has no `Total`, so `pool()` stops at page one; walk
     * with `items()`.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $customerId only this customer's credits
     * @param null|bool $showUsedCredits include the credits already used
     */
    public function paginate(
        ?int $limit = null,
        ?string $customerId = null,
        ?bool $showUsedCredits = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetCustomerCredits(
            null,
            $limit,
            $customerId,
            $showUsedCredits,
        ));
    }
}
