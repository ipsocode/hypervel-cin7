<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\SuspendReason;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\SuspendReason\SuspendReasonData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/suspendReason`, body is a `SuspendReasonData`; the response is the saved suspend
 * reason.
 *
 * @extends WriteRequest<SuspendReasonData>
 */
final class PutProductionSuspendReason extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'production/suspendReason';
    }

    public function createDtoFromResponse(Response $response): SuspendReasonData
    {
        return SuspendReasonData::from($response->json())->setResponse($response);
    }
}
