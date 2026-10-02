<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Reference\Discount;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Reference\Discount\ProductDiscountRuleData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET reference/discount` — the list envelope is keyed `DiscountRules`.
 *
 * @extends ListRequest<list<ProductDiscountRuleData>>
 */
final class GetDiscount extends ListRequest
{
    protected string $listKey = 'DiscountRules';

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
        return 'reference/discount';
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

    /**
     * @return list<ProductDiscountRuleData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): ProductDiscountRuleData => ProductDiscountRuleData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
