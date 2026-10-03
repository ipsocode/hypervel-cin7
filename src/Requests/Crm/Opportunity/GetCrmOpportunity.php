<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Crm\Opportunity;

use DateTimeInterface;
use Ipsocode\Cin7\Data\Crm\Opportunity\OpportunityData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET crm/opportunity` — the list envelope is keyed `opportunityList`.
 *
 * @extends ListRequest<OpportunityData>
 */
final class GetCrmOpportunity extends ListRequest
{
    protected string $listKey = 'opportunityList';

    protected string $item = OpportunityData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly DateTimeInterface|string|null $modifiedSince = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'crm/opportunity';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'ModifiedSince' => $this->modifiedSince,
        ];
    }
}
