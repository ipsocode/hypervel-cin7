<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Journal;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Journal\JournalData;
use Ipsocode\Cin7\Enums\CompletionStatus;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET journal` — the list envelope is keyed `Journals`.
 *
 * @extends ListRequest<list<JournalData>>
 */
final class GetJournal extends ListRequest
{
    protected string $listKey = 'Journals';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $taskId = null,
        protected readonly ?CompletionStatus $status = null,
        protected readonly ?string $search = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'journal';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'TaskID' => $this->taskId,
            'Status' => $this->status,
            'Search' => $this->search,
        ];
    }

    /**
     * @return list<JournalData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): JournalData => JournalData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
