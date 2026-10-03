<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\ProductionBom;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductFamilyProductionBomsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/productionBOM`, body is a `ProductFamilyProductionBomPostData`; the response is
 * the saved production BOMs of the product family.
 *
 * @extends WriteRequest<ProductFamilyProductionBomsData>
 */
final class PostProductFamilyProductionBom extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'production/productionBOM';
    }

    public function createDtoFromResponse(Response $response): ProductFamilyProductionBomsData
    {
        return ProductFamilyProductionBomsData::from($response->json())->setResponse($response);
    }
}
