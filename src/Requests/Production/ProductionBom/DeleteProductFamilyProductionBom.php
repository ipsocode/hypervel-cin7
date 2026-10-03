<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\ProductionBom;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `DELETE production/productionBOM?ProductFamilyID&BOMID`, deletes a production BOM of a product
 * family; the response is not documented.
 *
 * @extends Cin7Request<null>
 */
final class DeleteProductFamilyProductionBom extends Cin7Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected readonly string $productFamilyId,
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
            'ProductFamilyID' => $this->productFamilyId,
            'BOMID' => $this->bomId,
        ]);
    }
}
