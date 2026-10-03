<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\TaskCategory;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\TaskCategory\TaskCategoryData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST crm/taskcategory`, body is a `TaskCategoryPostData`; the response is a `Categories` list holding the saved record.
 *
 * @extends WriteRequest<list<TaskCategoryData>>
 */
final class PostCrmTaskCategory extends WriteRequest
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return 'crm/taskcategory';
    }

    /**
     * @return list<TaskCategoryData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): TaskCategoryData => TaskCategoryData::from($item)->setResponse($response),
            array_values($response->json('Categories')),
        );
    }
}
