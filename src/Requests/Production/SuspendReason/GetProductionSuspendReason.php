<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\SuspendReason;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\SuspendReason\SuspendReasonData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET production/suspendReason`, the list envelope is keyed `SuspendReasons`.
 *
 * @extends ListRequest<list<SuspendReasonData>>
 */
final class GetProductionSuspendReason extends ListRequest
{
    protected string $listKey = 'SuspendReasons';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $workcenterId = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'production/suspendReason';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'WorkcenterID' => $this->workcenterId,
        ];
    }

    /**
     * @return list<SuspendReasonData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): SuspendReasonData => SuspendReasonData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
