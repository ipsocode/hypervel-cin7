<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Sale;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT sale`, body is a `SalePutData` and carries `ID`; the response is the saved Sale.
 * `SaleType` is available only on POST, so it is left out of an array body.
 *
 * @extends WriteRequest<SaleData>
 */
final class PutSale extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = SaleData::class;

    /**
     * @var list<string>
     */
    protected array $omit = ['SaleType'];

    public function resolveEndpoint(): string
    {
        return 'sale';
    }
}
