<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Run\Void;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunUndoData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/order/run/void`, body is a `ProductionRunUndoData`; the response is the run,
 * with an `ErrorMessage` if it failed.
 *
 * @extends WriteRequest<ProductionRunUndoData>
 */
final class PutProductionOrderRunVoid extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = ProductionRunUndoData::class;

    public function resolveEndpoint(): string
    {
        return 'production/order/run/void';
    }
}
