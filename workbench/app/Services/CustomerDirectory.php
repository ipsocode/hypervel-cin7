<?php

declare(strict_types=1);

namespace Workbench\App\Services;

use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Customer\CustomerData;

/**
 * A consuming application's customer service, built on the connector.
 *
 * It exists for the seam, not the coverage: it proves `Cin7ServiceProvider`'s singleton
 * resolves as a constructor dependency.
 *
 * @see docs/testing.md
 */
final class CustomerDirectory
{
    public function __construct(private readonly Cin7Connector $connector)
    {
    }

    /**
     * Every customer, across all pages of `$limit`, optionally only those whose name starts with
     * `$name`.
     *
     * @return list<array<string, mixed>>
     */
    public function all(?int $limit = null, ?string $name = null): array
    {
        $paginator = $this->connector->customer()->paginate(limit: $limit, name: $name);

        return iterator_to_array($paginator->items(), false);
    }

    public function find(string $guid): ?CustomerData
    {
        return $this->connector->customer()->get(id: $guid)->dto()[0] ?? null;
    }

    public function create(CustomerData $customer): CustomerData
    {
        return $this->connector->customer()->post($customer)->dto();
    }

    /**
     * The GUID is applied to the body, so it wins over an `ID` already set on `$customer`.
     */
    public function update(string $guid, CustomerData $customer): CustomerData
    {
        // Rebuilt as a data object, not spread into an array: an array body is sent verbatim, so
        // every field the caller left unset would go out as null.
        return $this->connector->customer()->put(CustomerData::from([...$customer->toArray(), 'ID' => $guid]))->dto();
    }
}
