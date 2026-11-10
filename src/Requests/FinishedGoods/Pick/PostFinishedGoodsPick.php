<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoods\Pick;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\FinishedGoods\Pick\FinishedGoodsPickData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST finishedGoods/pick`, body is a `FinishedGoodsPickData`; the response is the saved pick.
 *
 * @extends WriteRequest<FinishedGoodsPickData>
 */
final class PostFinishedGoodsPick extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = FinishedGoodsPickData::class;

    public function resolveEndpoint(): string
    {
        return 'finishedGoods/pick';
    }
}
