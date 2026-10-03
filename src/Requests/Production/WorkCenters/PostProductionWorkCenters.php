<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\WorkCenters;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
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

    public function resolveEndpoint(): string
    {
        return 'production/workcenters';
    }

    public function createDtoFromResponse(Response $response): WorkCentersData
    {
        return WorkCentersData::from($response->json())->setResponse($response);
    }
}
