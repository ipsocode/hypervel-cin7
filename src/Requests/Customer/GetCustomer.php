<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Customer;

use DateTimeInterface;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Requests\ListRequest;

/**
 * `GET customer` — the list envelope is keyed `CustomerList`.
 *
 * @extends ListRequest<CustomerData>
 */
final class GetCustomer extends ListRequest
{
    protected string $listKey = 'CustomerList';

    protected string $item = CustomerData::class;

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
        protected readonly ?string $id = null,
        protected readonly ?string $name = null,
        protected readonly DateTimeInterface|string|null $modifiedSince = null,
        protected readonly ?bool $includeDeprecated = null,
        protected readonly ?bool $includeProductPrices = null,
        protected readonly ?string $contactFilter = null,
    ) {
        parent::__construct($page, $limit);
    }

    public function resolveEndpoint(): string
    {
        return 'customer';
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
            'IncludeProductPrices' => $this->includeProductPrices,
            'ContactFilter' => $this->contactFilter,
        ];
    }
}
