<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Lead;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\Lead\LeadData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT crm/lead`, body is a `LeadPutData` and carries the record's `ID`; the response is a `LeadList` list holding the saved record.
 *
 * @extends WriteRequest<list<LeadData>>
 */
final class PutCrmLead extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'crm/lead';
    }

    /**
     * @return list<LeadData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(LeadData::class, $response, $response->json('LeadList'));
    }
}
