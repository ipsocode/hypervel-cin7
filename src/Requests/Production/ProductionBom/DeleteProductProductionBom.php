<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\ProductionBom;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE production/productionBOM?ProductID&BOMID`, deletes a production BOM of a product; the
 * response is not documented.
 *
 * @extends Cin7Request<null>
 */
final class DeleteProductProductionBom extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $productId,
        protected readonly string $bomId,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'production/productionBOM';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'ProductID' => $this->productId,
            'BOMID' => $this->bomId,
        ]);
    }
}
