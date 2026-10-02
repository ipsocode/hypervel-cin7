<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\StockAdjustment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\StockAdjustment\StockAdjustmentData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET stockadjustment?TaskID`, one stock adjustment, with the lines it changed and the transactions they created.
 *
 * @extends Cin7Request<StockAdjustmentData>
 */
final class GetStockAdjustment extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
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
            'TaskID' => $this->taskId,
        ]);
    }

    public function createDtoFromResponse(Response $response): StockAdjustmentData
    {
        return StockAdjustmentData::from($response->json())->setResponse($response);
    }
}
