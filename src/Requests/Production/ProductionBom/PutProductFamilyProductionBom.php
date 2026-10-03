<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\ProductionBom;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductFamilyProductionBomsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT production/productionBOM`, body is a `ProductFamilyProductionBomPutData`; the response is
 * the production BOMs of the product family.
 *
 * @extends WriteRequest<ProductFamilyProductionBomsData>
 */
final class PutProductFamilyProductionBom extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'production/productionBOM';
    }

    public function createDtoFromResponse(Response $response): ProductFamilyProductionBomsData
    {
        return ProductFamilyProductionBomsData::from($response->json())->setResponse($response);
    }
}
