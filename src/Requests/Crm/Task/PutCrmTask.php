<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Task;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\Task\TaskData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT crm/task`, body is a `TaskPutData` and carries the record's `ID`; the response is a `Tasks` list holding the saved record.
 *
 * @extends WriteRequest<list<TaskData>>
 */
final class PutCrmTask extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'crm/task';
    }

    /**
     * @return list<TaskData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): TaskData => TaskData::from($item)->setResponse($response),
            array_values($response->json('Tasks')),
        );
    }
}
