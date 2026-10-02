<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\ProductFamily;

use DateTimeInterface;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\ProductFamily\ProductFamilyData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET productFamily` — the list envelope is keyed `ProductFamilies`. The reference spells the SKU
 * filter `Sku`.
 *
 * @extends ListRequest<list<ProductFamilyData>>
 */
final class GetProductFamily extends ListRequest
{
    protected string $listKey = 'ProductFamilies';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
        protected readonly ?string $sku = null,
        protected readonly DateTimeInterface|string|null $modifiedSince = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'productFamily';
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
            'ModifiedSince' => $this->modifiedSince,
        ];
    }

    /**
     * @return list<ProductFamilyData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): ProductFamilyData => ProductFamilyData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
