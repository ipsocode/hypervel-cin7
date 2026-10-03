<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Ref\Unit;

use Ipsocode\Cin7\Data\Ref\Unit\UnitOfMeasureData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET ref/unit` — the list envelope is keyed `UnitList`.
 *
 * @extends ListRequest<UnitOfMeasureData>
 */
final class GetUnit extends ListRequest
{
    protected string $listKey = 'UnitList';

    protected string $item = UnitOfMeasureData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $name = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'ref/unit';
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
