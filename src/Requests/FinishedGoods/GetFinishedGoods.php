<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoods;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\FinishedGoods\FinishedGoodsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET finishedGoods?TaskID`, one finished goods task, with its lines.
 *
 * @extends Cin7Request<FinishedGoodsData>
 */
final class GetFinishedGoods extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'finishedGoods';
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

    public function createDtoFromResponse(Response $response): FinishedGoodsData
    {
        return FinishedGoodsData::from($response->json())->setResponse($response);
    }
}
