<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockAdjustment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\StockAdjustment\StockAdjustmentData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST stockadjustment`, body is a `StockAdjustmentPostData`; the response is the saved stock adjustment.
 *
 * @extends WriteRequest<StockAdjustmentData>
 */
final class PostStockAdjustment extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'stockadjustment';
    }

    public function createDtoFromResponse(Response $response): StockAdjustmentData
    {
        return StockAdjustmentData::from($response->json())->setResponse($response);
    }
}
