<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Lead;

use DateTimeInterface;
use Ipsocode\Cin7\Data\Crm\Lead\LeadData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET crm/lead` — the list envelope is keyed `LeadList`.
 *
 * @extends ListRequest<LeadData>
 */
final class GetCrmLead extends ListRequest
{
    protected string $listKey = 'LeadList';

    protected string $item = LeadData::class;

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
}
