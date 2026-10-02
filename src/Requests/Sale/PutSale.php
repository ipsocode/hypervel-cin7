<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT sale`, body is a Sale and carries `ID`; the response is the saved Sale. `SaleType` is
 * available only on POST, so it is left out of the body.
 *
 * @extends WriteRequest<SaleData>
 */
final class PutSale extends WriteRequest
{
    protected Method $method = Method::PUT;

    /**
     * @var list<string>
     */
    protected array $omit = ['SaleType'];

    public function resolveEndpoint(): string
    {
        return 'sale';
    }

    public function createDtoFromResponse(Response $response): SaleData
    {
        return SaleData::from($response->json())->setResponse($response);
    }
}
