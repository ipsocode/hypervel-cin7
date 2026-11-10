<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTransfer;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\StockTransfer\StockTransferData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST stockTransfer`, body is a `StockTransferPostData`; the response is the saved stock transfer.
 *
 * @extends WriteRequest<StockTransferData>
 */
final class PostStockTransfer extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = StockTransferData::class;

    public function resolveEndpoint(): string
    {
        return 'stockTransfer';
    }
}
