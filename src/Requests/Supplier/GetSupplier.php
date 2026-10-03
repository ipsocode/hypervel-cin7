<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Supplier;

use DateTimeInterface;
use Ipsocode\Cin7\Data\Supplier\SupplierData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET supplier` — the list envelope is keyed `SupplierList`.
 *
 * @extends ListRequest<SupplierData>
 */
final class GetSupplier extends ListRequest
{
    protected string $listKey = 'SupplierList';

    protected string $item = SupplierData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
        protected readonly DateTimeInterface|string|null $modifiedSince = null,
        protected readonly ?bool $includeDeprecated = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'supplier';
    }

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {
        return [
            'ID' => $this->id,
            'Name' => $this->name,
            'ModifiedSince' => $this->modifiedSince,
            'IncludeDeprecated' => $this->includeDeprecated,
        ];
    }
}
