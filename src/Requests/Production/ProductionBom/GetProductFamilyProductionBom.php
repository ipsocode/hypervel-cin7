<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\ProductionBom;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductFamilyProductionBomsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET production/productionBOM?ProductFamilyID`, the production BOMs of a product family.
 *
 * @extends Cin7Request<ProductFamilyProductionBomsData>
 */
final class GetProductFamilyProductionBom extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $productFamilyId,
        protected readonly ?bool $returnAttachmentsContent = null,
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
            'ReturnAttachmentsContent' => $this->returnAttachmentsContent,
        ]);
    }

    public function createDtoFromResponse(Response $response): ProductFamilyProductionBomsData
    {
        return ProductFamilyProductionBomsData::from($response->json())->setResponse($response);
    }
}
