<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Stock;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Purchase\Stock\PurchaseStockData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET purchase/stock?TaskID`, a purchase's stock received.
 *
 * @extends Cin7Request<PurchaseStockData>
 */
final class GetPurchaseStock extends Cin7Request
{
    protected Method $method = Method::GET;

    protected ?string $dto = PurchaseStockData::class;

    public function __construct(
        protected readonly string $taskId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return 'purchase/stock';
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
