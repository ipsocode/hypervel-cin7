<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Opportunity;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\Opportunity\OpportunityData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT crm/opportunity`, body is a `OpportunityPutData` and carries the record's `ID`; the response is a `opportunityList` list holding the saved record.
 *
 * @extends WriteRequest<list<OpportunityData>>
 */
final class PutCrmOpportunity extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'crm/opportunity';
    }

    /**
     * @return list<OpportunityData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(OpportunityData::class, $response, $response->json('opportunityList'));
    }
}
