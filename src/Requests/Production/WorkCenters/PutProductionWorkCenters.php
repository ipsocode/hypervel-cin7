<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\WorkCenters;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Production\WorkCenters\WorkCentersData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/workcenters`, body is a `WorkCentersData`; the response is the saved work
 * centers, with any `WarningMessage`.
 *
 * @extends WriteRequest<WorkCentersData>
 */
final class PutProductionWorkCenters extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = WorkCentersData::class;

    public function resolveEndpoint(): string
    {
        return 'production/workcenters';
    }
}
