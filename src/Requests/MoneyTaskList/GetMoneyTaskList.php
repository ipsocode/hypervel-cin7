<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\MoneyTaskList;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\MoneyTaskList\MoneyTaskListData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET moneyTaskList` — the list envelope is keyed `MoneyTasks`.
 *
 * @extends ListRequest<list<MoneyTaskListData>>
 */
final class GetMoneyTaskList extends ListRequest
{
    protected string $listKey = 'MoneyTasks';

    public function resolveEndpoint(): string
    {
        return 'moneyTaskList';
    }

    /**
     * @return list<MoneyTaskListData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): MoneyTaskListData => MoneyTaskListData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
