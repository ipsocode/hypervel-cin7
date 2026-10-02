<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Product\MarkupPrices;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Product\MarkupPrices\MarkupPricesData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET product/markupprices?ProductID`, a product's markup prices: one line for each of the ten
 * price tiers, a tier without a markup having `MarkupType` `D`.
 *
 * @extends Cin7Request<MarkupPricesData>
 */
final class GetProductMarkupPrices extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $productId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'product/markupprices';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ProductID' => $this->productId,
        ]);
    }

    public function createDtoFromResponse(Response $response): MarkupPricesData
    {
        return MarkupPricesData::from($response->json())->setResponse($response);
    }
}
