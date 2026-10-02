<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockTake;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\StockTake\StockTakeData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET stocktake?TaskID`, one stock take, with its filters, the products it counts and the transactions it created.
 *
 * @extends Cin7Request<StockTakeData>
 */
final class GetStockTake extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
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
            'TaskID' => $this->taskId,
        ]);
    }

    public function createDtoFromResponse(Response $response): StockTakeData
    {
        return StockTakeData::from($response->json())->setResponse($response);
    }
}
