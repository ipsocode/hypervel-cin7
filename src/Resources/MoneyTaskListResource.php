<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Enums\MoneyTaskType;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\MoneyTaskList\GetMoneyTaskList;

/**
 * `moneyTaskList`, the money tasks.
 */
final class MoneyTaskListResource extends ListResource
{
    /**
     * One page of money tasks; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|CompletionStatus $status only money tasks with this status
     * @param null|string $search only money tasks with this text in the supplier or customer name,
     *                            reference, bank account code, memo, total or a custom field
     * @param null|MoneyTaskType $taskType only money tasks of this type
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?CompletionStatus $status = null,
        ?string $search = null,
        ?MoneyTaskType $taskType = null,
    ): Response {
        return $this->sendList(new GetMoneyTaskList(
            $page,
            $limit,
            $status,
            $search,
            $taskType,
        ));
    }

    /**
     * Every page of money tasks, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|CompletionStatus $status only money tasks with this status
     * @param null|string $search only money tasks with this text in the supplier or customer name,
     *                            reference, bank account code, memo, total or a custom field
     * @param null|MoneyTaskType $taskType only money tasks of this type
     */
    public function paginate(
        ?int $limit = null,
        ?CompletionStatus $status = null,
        ?string $search = null,
        ?MoneyTaskType $taskType = null,
    ): Cin7Paginator {
        return $this->paginateList(new GetMoneyTaskList(
            null,
            $limit,
            $status,
            $search,
            $taskType,
        ));
    }
}
