<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\Stock;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStocksData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET advanced-purchase/stock?PurchaseID`, an advanced purchase's stock receiving tasks.
 *
 * @extends Cin7Request<AdvancedPurchaseStocksData>
 */
final class GetAdvancedPurchaseStock extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $purchaseId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/stock';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'PurchaseID' => $this->purchaseId,
        ]);
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchaseStocksData
    {
        return AdvancedPurchaseStocksData::from($response->json())->setResponse($response);
    }
}
