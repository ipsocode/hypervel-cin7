<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoods;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\FinishedGoods\FinishedGoodsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT finishedGoods`, body is a `FinishedGoodsPutData`; the response is the saved finished goods
 * task.
 *
 * @extends WriteRequest<FinishedGoodsData>
 */
final class PutFinishedGoods extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'finishedGoods';
    }

    public function createDtoFromResponse(Response $response): FinishedGoodsData
    {
        return FinishedGoodsData::from($response->json())->setResponse($response);
    }
}
