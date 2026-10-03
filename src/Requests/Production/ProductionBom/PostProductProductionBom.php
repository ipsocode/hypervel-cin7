<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\ProductionBom;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductProductionBomsData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST production/productionBOM`, body is a `ProductProductionBomPostData`; the response is the
 * saved production BOMs of the product.
 *
 * @extends WriteRequest<ProductProductionBomsData>
 */
final class PostProductProductionBom extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'production/productionBOM';
    }

    public function createDtoFromResponse(Response $response): ProductProductionBomsData
    {
        return ProductProductionBomsData::from($response->json())->setResponse($response);
    }
}
