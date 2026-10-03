<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\MoneyTaskList;

use Ipsocode\Cin7\Data\MoneyTaskList\MoneyTaskListData;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Enums\MoneyTaskType;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET moneyTaskList` — the list envelope is keyed `MoneyTasks`.
 *
 * @extends ListRequest<MoneyTaskListData>
 */
final class GetMoneyTaskList extends ListRequest
{
    protected string $listKey = 'MoneyTasks';

    protected string $item = MoneyTaskListData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?CompletionStatus $status = null,
        protected readonly ?string $search = null,
        protected readonly ?MoneyTaskType $taskType = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'moneyTaskList';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Status' => $this->status,
            'Search' => $this->search,
            'TaskType' => $this->taskType,
        ];
    }
}
