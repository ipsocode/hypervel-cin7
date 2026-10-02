<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Workflow;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\Workflow\WorkflowData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST crm/workflow`, body is a `WorkflowPostData`; the response is a `Workflows` list holding the saved record.
 *
 * @extends WriteRequest<list<WorkflowData>>
 */
final class PostCrmWorkflow extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'crm/workflow';
    }

    /**
     * @return list<WorkflowData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): WorkflowData => WorkflowData::from($item)->setResponse($response),
            array_values($response->json('Workflows')),
        );
    }
}
