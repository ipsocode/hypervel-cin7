<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\SuspendReason;

use Ipsocode\Cin7\Data\Production\SuspendReason\SuspendReasonData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET production/suspendReason`, the list envelope is keyed `SuspendReasons`.
 *
 * @extends ListRequest<SuspendReasonData>
 */
final class GetProductionSuspendReason extends ListRequest
{
    protected string $listKey = 'SuspendReasons';

    protected string $item = SuspendReasonData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $workCenterId = null,
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
            'WorkcenterID' => $this->workCenterId,
        ];
    }
}
