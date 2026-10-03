<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\ProductAvailability;

use Ipsocode\Cin7\Data\Ref\ProductAvailability\ProductAvailabilityData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/productavailability` — the list envelope is keyed `ProductAvailabilityList`. The
 * reference spells the SKU filter `Sku`.
 *
 * @extends ListRequest<ProductAvailabilityData>
 */
final class GetProductAvailability extends ListRequest
{
    protected string $listKey = 'ProductAvailabilityList';

    protected string $item = ProductAvailabilityData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
        protected readonly ?string $sku = null,
        protected readonly ?string $location = null,
        protected readonly ?string $batch = null,
        protected readonly ?string $category = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/productavailability';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
            'Sku' => $this->sku,
            'Location' => $this->location,
            'Batch' => $this->batch,
            'Category' => $this->category,
        ];
    }
}
