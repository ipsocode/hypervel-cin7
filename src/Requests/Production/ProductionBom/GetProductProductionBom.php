<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\ProductionBom;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductProductionBomsData;
use Ipsocode\Cin7\Requests\Cin7Request;

/**
 * `GET production/productionBOM?ProductID`, the production BOMs of a product.
 *
 * @extends Cin7Request<ProductProductionBomsData>
 */
final class GetProductProductionBom extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $productId,
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
            'ProductID' => $this->productId,
            'ReturnAttachmentsContent' => $this->returnAttachmentsContent,
        ]);
    }

    public function createDtoFromResponse(Response $response): ProductProductionBomsData
    {
        return ProductProductionBomsData::from($response->json())->setResponse($response);
    }
}
