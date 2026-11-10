<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\FinishedGoods;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\FinishedGoods\FinishedGoodsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST finishedGoods`, body is a `FinishedGoodsPostData`; the response is the saved finished
 * goods task.
 *
 * @extends WriteRequest<FinishedGoodsData>
 */
final class PostFinishedGoods extends WriteRequest
{
    protected Method $method = Method::POST;

    protected ?string $dto = FinishedGoodsData::class;

    public function resolveEndpoint(): string
    {
        return 'finishedGoods';
    }
}
