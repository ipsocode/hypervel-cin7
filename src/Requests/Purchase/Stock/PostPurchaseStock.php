<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Stock;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Purchase\Stock\PurchaseStockData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST purchase/stock`, body is a `PurchaseStockPostData` or an array; the response is the saved
 * stock received. A line's `Name` and `Received` are read-only, so they are left out of the body.
 * Cin7 rejects it unless the order is `AUTHORISED` and the stock received is `DRAFT` or
 * `NOT AVAILABLE`.
 *
 * @extends WriteRequest<PurchaseStockData>
 */
final class PostPurchaseStock extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = ['Lines.*.Name', 'Lines.*.Received'];

    public function resolveEndpoint(): string
    {
        return 'purchase/stock';
    }

    public function createDtoFromResponse(Response $response): PurchaseStockData
    {
        return PurchaseStockData::from($response->json())->setResponse($response);
    }
}
