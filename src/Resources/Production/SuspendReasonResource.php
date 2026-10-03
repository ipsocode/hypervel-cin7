<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Production;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\SuspendReason\SuspendReasonData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Production\SuspendReason\GetProductionSuspendReason;
use Ipsocode\Cin7\Requests\Production\SuspendReason\PutProductionSuspendReason;

/**
 * `production/suspendReason`, the suspendReason resource.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class SuspendReasonResource extends BaseResource
{
    /**
     * One page of SuspendReasons; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $workcenterId only reasons of this work center
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $workcenterId = null,
    ): Response {
        return $this->connector->send(new GetProductionSuspendReason(
            $page,
            $limit,
            $workcenterId,
        ));
    }

    /**
     * Every page of SuspendReasons, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $workcenterId only reasons of this work center
     */
    public function paginate(
        ?int $limit = null,
        ?string $workcenterId = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetProductionSuspendReason(
            null,
            $limit,
            $workcenterId,
        ));
    }

    /**
     * @param array<string, mixed>|SuspendReasonData $body
     */
    public function put(array|SuspendReasonData $body): Response
    {
        return $this->connector->send(new PutProductionSuspendReason($body));
    }
}
