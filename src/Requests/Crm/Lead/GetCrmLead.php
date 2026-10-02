<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Lead;

use DateTimeInterface;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Crm\Lead\LeadData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET crm/lead` — the list envelope is keyed `LeadList`.
 *
 * @extends ListRequest<list<LeadData>>
 */
final class GetCrmLead extends ListRequest
{
    protected string $listKey = 'LeadList';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
        protected readonly DateTimeInterface|string|null $modifiedSince = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'crm/lead';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
            'ModifiedSince' => $this->modifiedSince,
        ];
    }

    /**
     * @return list<LeadData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): LeadData => LeadData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
