<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoods;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\FinishedGoods\FinishedGoodsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE finishedGoods?ID&Void`, voids or undoes a finished goods task; the response is the task.
 *
 * @extends Cin7Request<FinishedGoodsData>
 */
final class DeleteFinishedGoods extends Cin7Request
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
        return 'finishedGoods';
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

    public function createDtoFromResponse(Response $response): FinishedGoodsData
    {
        return FinishedGoodsData::from($response->json())->setResponse($response);
    }
}
