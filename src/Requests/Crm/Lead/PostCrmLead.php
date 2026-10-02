<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Lead;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\Lead\LeadData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST crm/lead`, body is a `LeadPostData`; the response is a `LeadList` list holding the saved record.
 *
 * @extends WriteRequest<list<LeadData>>
 */
final class PostCrmLead extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'crm/lead';
    }

    /**
     * @return list<LeadData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): LeadData => LeadData::from($item)->setResponse($response),
            array_values($response->json('LeadList')),
        );
    }
}
