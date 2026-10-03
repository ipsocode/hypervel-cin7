<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\Deals;

use Ipsocode\Cin7\Data\Reference\Deals\ProductDealData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET reference/deals` — the list envelope is keyed `Deals`.
 *
 * @extends ListRequest<ProductDealData>
 */
final class GetDeals extends ListRequest
{
    protected string $listKey = 'Deals';

    protected string $item = ProductDealData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $search = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'reference/deals';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Search' => $this->search,
        ];
    }
}
