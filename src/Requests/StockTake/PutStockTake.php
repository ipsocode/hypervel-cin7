<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTake;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\StockTake\StockTakeData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT stocktake`, body is a `StockTakePutData`; the response is the saved stock take.
 *
 * @extends WriteRequest<StockTakeData>
 */
final class PutStockTake extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = StockTakeData::class;

    public function resolveEndpoint(): string
    {
        return 'stocktake';
    }
}
