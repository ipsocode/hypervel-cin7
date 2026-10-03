<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Category;

use Ipsocode\Cin7\Data\Ref\Category\ProductCategoryData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/category` — the list envelope is keyed `CategoryList`.
 *
 * @extends ListRequest<ProductCategoryData>
 */
final class GetCategory extends ListRequest
{
    protected string $listKey = 'CategoryList';

    protected string $item = ProductCategoryData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $name = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/category';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'Name' => $this->name,
        ];
    }
}
