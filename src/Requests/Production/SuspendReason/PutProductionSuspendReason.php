<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\SuspendReason;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = SuspendReasonData::class;

    public function resolveEndpoint(): string
    {
        return 'production/suspendReason';
    }
}
