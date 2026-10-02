<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Crm;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Crm\TaskCategory\TaskCategoryPostData;
use Ipsocode\Cin7\Data\Crm\TaskCategory\TaskCategoryPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Crm\TaskCategory\GetCrmTaskCategory;
use Ipsocode\Cin7\Requests\Crm\TaskCategory\PostCrmTaskCategory;
use Ipsocode\Cin7\Requests\Crm\TaskCategory\PutCrmTaskCategory;

/**
 * `crm/taskcategory`, the task categories.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class TaskCategoryResource extends BaseResource
{
    /**
     * One page of task categories; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the category with this ID
     * @param null|string $name only categories whose name starts with this
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
    ): Response {
        return $this->connector->send(new GetCrmTaskCategory(
            $page,
            $limit,
            $id,
            $name,
        ));
    }

    /**
     * Every page of task categories, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the category with this ID
     * @param null|string $name only categories whose name starts with this
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetCrmTaskCategory(
            null,
            $limit,
            $id,
            $name,
        ));
    }

    /**
     * @param array<string, mixed>|TaskCategoryPostData $body
     */
    public function post(array|TaskCategoryPostData $body): Response
    {
        return $this->connector->send(new PostCrmTaskCategory($body));
    }

    /**
     * @param array<string, mixed>|TaskCategoryPutData $body
     */
    public function put(array|TaskCategoryPutData $body): Response
    {
        return $this->connector->send(new PutCrmTaskCategory($body));
    }
}
