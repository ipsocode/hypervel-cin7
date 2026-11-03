<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockAdjustment;

use Hypervel\Saloon\Enums\Method;
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

    protected ?string $dto = StockAdjustmentData::class;

    public function resolveEndpoint(): string
    {
        return 'stockadjustment';
    }
}
