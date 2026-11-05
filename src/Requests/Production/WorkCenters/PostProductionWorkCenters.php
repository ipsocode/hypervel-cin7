<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\WorkCenters;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Production\WorkCenters\WorkCentersData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/workcenters`, body is a `WorkCentersData`; the response is the saved work
 * centers, under `Workcenters`.
 *
 * @extends WriteRequest<WorkCentersData>
 */
final class PostProductionWorkCenters extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = WorkCentersData::class;

    public function resolveEndpoint(): string
    {
        return 'production/workcenters';
    }
}
