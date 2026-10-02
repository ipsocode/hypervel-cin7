<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Workflow;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\Workflow\WorkflowData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT crm/workflow`, body is a `WorkflowPutData` and carries the record's `ID`; the response is a `Workflows` list holding the saved record.
 *
 * @extends WriteRequest<list<WorkflowData>>
 */
final class PutCrmWorkflow extends WriteRequest
{
    protected Method $method = Method::PUT;

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
