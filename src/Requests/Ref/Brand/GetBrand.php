<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Brand;

use Ipsocode\Cin7\Data\Ref\Brand\BrandData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/brand` — the list envelope is keyed `BrandList`.
 *
 * @extends ListRequest<BrandData>
 */
final class GetBrand extends ListRequest
{
    protected string $listKey = 'BrandList';

    protected string $item = BrandData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $name = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/brand';
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
