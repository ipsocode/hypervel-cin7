<?php

declare(strict_types=1);

namespace Workbench\App\Services;

use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Requests\CreateRecord;
use Ipsocode\Cin7\Requests\FindRecord;
use Ipsocode\Cin7\Requests\ListRecords;
use Ipsocode\Cin7\Requests\UpdateRecord;

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
     * Every customer, across all pages.
     *
     * @param array<string, mixed> $filters
     * @return list<array<string, mixed>>
     */
    public function all(array $filters = []): array
    {
        $paginator = $this->connector->paginate(new ListRecords(Endpoint::Customer, $filters));

        return iterator_to_array($paginator->items(), false);
    }

    /**
     * @return null|array<string, mixed>
     */
    public function find(string $guid): ?array
    {
        $response = $this->connector->send(new FindRecord(Endpoint::Customer, $guid));

        return $response->json('CustomerList')[0] ?? null;
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    public function create(array $attributes): array
    {
        return $this->connector->send(new CreateRecord(Endpoint::Customer, $attributes))->json();
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    public function update(string $guid, array $attributes): array
    {
        return $this->connector->send(new UpdateRecord(Endpoint::Customer, $guid, $attributes))->json();
    }
}
