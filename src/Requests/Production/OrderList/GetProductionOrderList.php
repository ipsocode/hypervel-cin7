<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Production\OrderList;

use DateTimeInterface;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Production\OrderList\ProductionOrderListData;
use Ipsocode\Cin7\Enums\ProductionOrderListStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET production/orderList`, the list envelope is keyed `ProductionOrderListItems`.
 *
 * @extends ListRequest<list<ProductionOrderListData>>
 */
final class GetProductionOrderList extends ListRequest
{
    protected string $listKey = 'ProductionOrderListItems';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?ProductionOrderListStatus $status = null,
        protected readonly ?string $search = null,
        protected readonly ?string $locationId = null,
        protected readonly DateTimeInterface|string|null $requiredByDateFrom = null,
        protected readonly DateTimeInterface|string|null $requiredByDateTo = null,
        protected readonly DateTimeInterface|string|null $completionDateFrom = null,
        protected readonly DateTimeInterface|string|null $completionDateTo = null,
        protected readonly ?string $sourceTaskId = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'production/orderList';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Status' => $this->status,
            'Search' => $this->search,
            'LocationID' => $this->locationId,
            'RequiredByDateFrom' => $this->requiredByDateFrom,
            'RequiredByDateTo' => $this->requiredByDateTo,
            'CompletionDateFrom' => $this->completionDateFrom,
            'CompletionDateTo' => $this->completionDateTo,
            'SourceTaskID' => $this->sourceTaskId,
        ];
    }

    /**
     * @return list<ProductionOrderListData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): ProductionOrderListData => ProductionOrderListData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
