<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockAdjustment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\StockAdjustment\StockAdjustmentData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE stockadjustment?ID&Void`, voids or undoes a stock adjustment; the response is the stock adjustment.
 *
 * @extends Cin7Request<StockAdjustmentData>
 */
final class DeleteStockAdjustment extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $id,
        protected readonly ?bool $void = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'stockadjustment';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ID' => $this->id,
            'Void' => $this->void,
        ]);
    }

    public function createDtoFromResponse(Response $response): StockAdjustmentData
    {
        return StockAdjustmentData::from($response->json())->setResponse($response);
    }
}
