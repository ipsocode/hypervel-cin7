<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\CustomPrices;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE custom-prices?ProductID&CustomerID`, deletes one customer's custom price for a product; the response `{Success}` is left to `json()`.
 *
 * @extends Cin7Request<null>
 */
final class DeleteCustomPrices extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $productId,
        protected readonly string $customerId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'custom-prices';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ProductID' => $this->productId,
            'CustomerID' => $this->customerId,
        ]);
    }
}
