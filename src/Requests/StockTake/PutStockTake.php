<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTake;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
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

    public function resolveEndpoint(): string
    {
        return 'stocktake';
    }

    public function createDtoFromResponse(Response $response): StockTakeData
    {
        return StockTakeData::from($response->json())->setResponse($response);
    }
}
