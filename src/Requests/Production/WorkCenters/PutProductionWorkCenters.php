<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\WorkCenters;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
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

    public function resolveEndpoint(): string
    {
        return 'production/workcenters';
    }

    public function createDtoFromResponse(Response $response): WorkCentersData
    {
        return WorkCentersData::from($response->json())->setResponse($response);
    }
}
