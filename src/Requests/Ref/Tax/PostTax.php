<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Tax;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST ref/tax`, body is a `TaxPostData`; the response is the list envelope holding the saved
 * rule. Cin7 marks `TaxPercent` read-only, so it is left out of the body.
 *
 * @extends WriteRequest<TaxData>
 */
final class PostTax extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = ['TaxPercent'];

    public function resolveEndpoint(): string
    {
        return 'ref/tax';
    }

    public function createDtoFromResponse(Response $response): TaxData
    {
        return TaxData::from($response->json('TaxRuleList.0'))->setResponse($response);
    }
}
