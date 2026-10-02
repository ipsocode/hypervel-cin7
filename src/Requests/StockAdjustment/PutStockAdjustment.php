<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockAdjustment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\StockAdjustment\StockAdjustmentData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT stockadjustment`, body is a `StockAdjustmentPutData`; the response is the saved stock adjustment.
 *
 * @extends WriteRequest<StockAdjustmentData>
 */
final class PutStockAdjustment extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'stockadjustment';
    }

    public function createDtoFromResponse(Response $response): StockAdjustmentData
    {
        return StockAdjustmentData::from($response->json())->setResponse($response);
    }
}
