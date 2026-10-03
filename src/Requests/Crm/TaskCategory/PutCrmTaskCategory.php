<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\TaskCategory;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\TaskCategory\TaskCategoryData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT crm/taskcategory`, body is a `TaskCategoryPutData` and carries the record's `ID`; the response is a `Categories` list holding the saved record.
 *
 * @extends WriteRequest<list<TaskCategoryData>>
 */
final class PutCrmTaskCategory extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'crm/taskcategory';
    }

    /**
     * @return list<TaskCategoryData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return $this->listOf(TaskCategoryData::class, $response, $response->json('Categories'));
    }
}
