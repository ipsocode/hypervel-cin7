<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Tax;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT ref/tax`, body is a Tax rule and carries `ID`; the response is the list envelope holding the saved rule.
 *
 * @extends WriteRequest<TaxData>
 */
final class PutTax extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'ref/tax';
    }

    public function createDtoFromResponse(Response $response): TaxData
    {
        return TaxData::from($response->json('TaxRuleList.0'))->setResponse($response);
    }
}
