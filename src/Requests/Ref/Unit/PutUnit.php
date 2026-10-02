<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Unit;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Unit\UnitOfMeasureData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT ref/unit`, body is a `UnitOfMeasurePutData` and carries the unit of measure's `ID`; the
 * response is the saved unit of measure, not a list.
 *
 * @extends WriteRequest<UnitOfMeasureData>
 */
final class PutUnit extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'ref/unit';
    }

    public function createDtoFromResponse(Response $response): UnitOfMeasureData
    {
        return UnitOfMeasureData::from($response->json())->setResponse($response);
    }
}
