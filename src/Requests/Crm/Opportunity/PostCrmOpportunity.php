<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Opportunity;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\Opportunity\OpportunityData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST crm/opportunity`, body is a `OpportunityPostData`; the response is a `opportunityList` list holding the saved record.
 *
 * @extends WriteRequest<list<OpportunityData>>
 */
final class PostCrmOpportunity extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'crm/opportunity';
    }

    /**
     * @return list<OpportunityData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): OpportunityData => OpportunityData::from($item)->setResponse($response),
            array_values($response->json('opportunityList')),
        );
    }
}
