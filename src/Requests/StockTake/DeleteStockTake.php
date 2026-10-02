<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTake;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\StockTake\StockTakeData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE stocktake?ID&Void`, voids or undoes a stock take; the response is the stock take.
 *
 * @extends Cin7Request<StockTakeData>
 */
final class DeleteStockTake extends Cin7Request
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
        return 'stocktake';
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

    public function createDtoFromResponse(Response $response): StockTakeData
    {
        return StockTakeData::from($response->json())->setResponse($response);
    }
}
