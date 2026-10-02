<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\FixedAssetType;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\FixedAssetType\FixedAssetTypeData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/fixedassettype` — the list envelope is keyed `FixedAssetTypeList`.
 *
 * @extends ListRequest<list<FixedAssetTypeData>>
 */
final class GetFixedAssetType extends ListRequest
{
    protected string $listKey = 'FixedAssetTypeList';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $fixedAssetTypeId = null,
        protected readonly ?string $name = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/fixedassettype';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'FixedAssetTypeID' => $this->fixedAssetTypeId,
            'Name' => $this->name,
        ];
    }

    /**
     * @return list<FixedAssetTypeData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): FixedAssetTypeData => FixedAssetTypeData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
