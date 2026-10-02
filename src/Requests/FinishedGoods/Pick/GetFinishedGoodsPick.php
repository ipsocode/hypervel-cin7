<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoods\Pick;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\FinishedGoods\Pick\FinishedGoodsPickData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET finishedGoods/pick?TaskID`, a finished goods task's pick.
 *
 * @extends Cin7Request<FinishedGoodsPickData>
 */
final class GetFinishedGoodsPick extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $taskId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'finishedGoods/pick';
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

    public function createDtoFromResponse(Response $response): FinishedGoodsPickData
    {
        return FinishedGoodsPickData::from($response->json())->setResponse($response);
    }
}
