<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Product;

use DateTimeInterface;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET product` — the list envelope is keyed `Products`.
 *
 * @extends ListRequest<list<ProductData>>
 */
final class GetProduct extends ListRequest
{
    protected string $listKey = 'Products';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
        protected readonly ?string $sku = null,
        protected readonly DateTimeInterface|string|null $modifiedSince = null,
        protected readonly ?bool $includeDeprecated = null,
        protected readonly ?bool $includeBom = null,
        protected readonly ?bool $includeSuppliers = null,
        protected readonly ?bool $includeMovements = null,
        protected readonly ?bool $includeAttachments = null,
        protected readonly ?bool $includeReorderLevels = null,
        protected readonly ?bool $includeCustomPrices = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'product';
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
            'IncludeDeprecated' => $this->includeDeprecated,
            'IncludeBOM' => $this->includeBom,
            'IncludeSuppliers' => $this->includeSuppliers,
            'IncludeMovements' => $this->includeMovements,
            'IncludeAttachments' => $this->includeAttachments,
            'IncludeReorderLevels' => $this->includeReorderLevels,
            'IncludeCustomPrices' => $this->includeCustomPrices,
        ];
    }

    /**
     * @return list<ProductData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): ProductData => ProductData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
