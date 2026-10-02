<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Crm;

use DateTimeInterface;
use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Crm\Task\TaskPostData;
use Ipsocode\Cin7\Data\Crm\Task\TaskPutData;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Crm\Task\GetCrmTask;
use Ipsocode\Cin7\Requests\Crm\Task\PostCrmTask;
use Ipsocode\Cin7\Requests\Crm\Task\PutCrmTask;

/**
 * `crm/task`, the tasks.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class TaskResource extends BaseResource
{
    /**
     * One page of tasks; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the task with this ID
     * @param null|string $name only tasks whose name starts with this
     * @param null|DateTimeInterface|string $startDateFrom only tasks starting at or after this
     * @param null|DateTimeInterface|string $startDateTo only tasks starting at or before this
     * @param null|DateTimeInterface|string $endDateFrom only tasks ending at or after this
     * @param null|DateTimeInterface|string $endDateTo only tasks ending at or before this
     * @param null|DateTimeInterface|string $completeDateFrom only tasks completed at or after this
     * @param null|DateTimeInterface|string $completeDateTo only tasks completed at or before this
     * @param null|string $assignedTo only tasks assigned to this user
     * @param null|string $category only tasks of this category
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        DateTimeInterface|string|null $startDateFrom = null,
        DateTimeInterface|string|null $startDateTo = null,
        DateTimeInterface|string|null $endDateFrom = null,
        DateTimeInterface|string|null $endDateTo = null,
        DateTimeInterface|string|null $completeDateFrom = null,
        DateTimeInterface|string|null $completeDateTo = null,
        ?string $assignedTo = null,
        ?string $category = null,
    ): Response {
        return $this->connector->send(new GetCrmTask(
            $page,
            $limit,
            $id,
            $name,
            $startDateFrom,
            $startDateTo,
            $endDateFrom,
            $endDateTo,
            $completeDateFrom,
            $completeDateTo,
            $assignedTo,
            $category,
        ));
    }

    /**
     * Every page of tasks, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the task with this ID
     * @param null|string $name only tasks whose name starts with this
     * @param null|DateTimeInterface|string $startDateFrom only tasks starting at or after this
     * @param null|DateTimeInterface|string $startDateTo only tasks starting at or before this
     * @param null|DateTimeInterface|string $endDateFrom only tasks ending at or after this
     * @param null|DateTimeInterface|string $endDateTo only tasks ending at or before this
     * @param null|DateTimeInterface|string $completeDateFrom only tasks completed at or after this
     * @param null|DateTimeInterface|string $completeDateTo only tasks completed at or before this
     * @param null|string $assignedTo only tasks assigned to this user
     * @param null|string $category only tasks of this category
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        DateTimeInterface|string|null $startDateFrom = null,
        DateTimeInterface|string|null $startDateTo = null,
        DateTimeInterface|string|null $endDateFrom = null,
        DateTimeInterface|string|null $endDateTo = null,
        DateTimeInterface|string|null $completeDateFrom = null,
        DateTimeInterface|string|null $completeDateTo = null,
        ?string $assignedTo = null,
        ?string $category = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetCrmTask(
            null,
            $limit,
            $id,
            $name,
            $startDateFrom,
            $startDateTo,
            $endDateFrom,
            $endDateTo,
            $completeDateFrom,
            $completeDateTo,
            $assignedTo,
            $category,
        ));
    }

    /**
     * @param array<string, mixed>|TaskPostData $body
     */
    public function post(array|TaskPostData $body): Response
    {
        return $this->connector->send(new PostCrmTask($body));
    }

    /**
     * @param array<string, mixed>|TaskPutData $body
     */
    public function put(array|TaskPutData $body): Response
    {
        return $this->connector->send(new PutCrmTask($body));
    }
}
