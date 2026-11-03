<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTransfer;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\StockTransfer\StockTransferData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET stockTransfer?TaskID`, one stock transfer, with its lines and its order.
 *
 * @extends Cin7Request<StockTransferData>
 */
final class GetStockTransfer extends Cin7Request
{
    protected Method $method = Method::GET;

    protected ?string $dto = StockTransferData::class;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'stockTransfer';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'TaskID' => $this->taskId,
        ]);
    }
}
