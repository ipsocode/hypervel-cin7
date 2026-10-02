<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\SaleCreditNoteList;

use DateTimeInterface;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\SaleCreditNoteList\SaleCreditNoteListData;
use Ipsocode\Cin7\Enums\SaleStatus;
use Ipsocode\Cin7\Enums\TaskStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET saleCreditNoteList`, the sales with a credit note; the list envelope is keyed `SaleList`,
 * as in `saleList`.
 *
 * @extends ListRequest<list<SaleCreditNoteListData>>
 */
final class GetSaleCreditNoteList extends ListRequest
{
    protected string $listKey = 'SaleList';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $search = null,
        protected readonly DateTimeInterface|string|null $createdSince = null,
        protected readonly DateTimeInterface|string|null $updatedSince = null,
        protected readonly DateTimeInterface|string|null $updatedUntil = null,
        protected readonly ?TaskStatus $creditNoteStatus = null,
        protected readonly ?SaleStatus $status = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'saleCreditNoteList';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Search' => $this->search,
            'CreatedSince' => $this->createdSince,
            'UpdatedSince' => $this->updatedSince,
            'UpdatedUntil' => $this->updatedUntil,
            'CreditNoteStatus' => $this->creditNoteStatus,
            'Status' => $this->status,
        ];
    }

    /**
     * @return list<SaleCreditNoteListData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): SaleCreditNoteListData => SaleCreditNoteListData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
