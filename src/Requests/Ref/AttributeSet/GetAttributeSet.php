<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\AttributeSet;

use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\Ref\AttributeSet\AttributeSetData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/attributeset` — the list envelope is keyed `AttributeSetList`.
 *
 * @extends ListRequest<list<AttributeSetData>>
 */
final class GetAttributeSet extends ListRequest
{
    protected string $listKey = 'AttributeSetList';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/attributeset';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
        ];
    }

    /**
     * @return list<AttributeSetData>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $item): AttributeSetData => AttributeSetData::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }
}
