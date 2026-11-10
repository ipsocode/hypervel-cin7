<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\Order\Run\Undo;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Production\Order\Run\ProductionRunUndoData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/order/run/undo`, body is a `ProductionRunUndoData`; the response is the run,
 * with an `ErrorMessage` if it failed.
 *
 * @extends WriteRequest<ProductionRunUndoData>
 */
final class PutProductionOrderRunUndo extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = ProductionRunUndoData::class;

    public function resolveEndpoint(): string
    {
        return 'production/order/run/undo';
    }
}
