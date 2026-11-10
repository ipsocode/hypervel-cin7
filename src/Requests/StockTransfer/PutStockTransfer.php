<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTransfer;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\StockTransfer\StockTransferData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT stockTransfer`, body is a `StockTransferPutData`; the response is the saved stock transfer.
 *
 * @extends WriteRequest<StockTransferData>
 */
final class PutStockTransfer extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = StockTransferData::class;

    public function resolveEndpoint(): string
    {
        return 'stockTransfer';
    }
}
