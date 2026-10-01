<?php

declare(strict_types=1);

namespace Workbench\App\Services;

use Ipsocode\Cin7\Cin7Connector;

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
        $paginator = $this->connector->customer()->paginate($filters);

        return iterator_to_array($paginator->items(), false);
    }

    /**
     * @return null|array<string, mixed>
     */
    public function find(string $guid): ?array
    {
        $response = $this->connector->customer()->get(['ID' => $guid]);

        return $response->json('CustomerList')[0] ?? null;
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    public function create(array $attributes): array
    {
        return $this->connector->customer()->post($attributes)->json();
    }

    /**
     * @param array<string, mixed> $attributes
     * @return array<string, mixed>
     */
    public function update(string $guid, array $attributes): array
    {
        // Assigned last so the GUID wins over a caller-supplied value under the same key.
        $attributes['ID'] = $guid;

        return $this->connector->customer()->put($attributes)->json();
    }
}
