<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use Hypervel\Foundation\Testing\RefreshDatabase;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Response;
use Hypervel\Support\Carbon;
use Hypervel\Support\Collection;
use Hypervel\Support\Sleep;
use Hypervel\Testbench\Attributes\WithConfig;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\Payload;
use Ipsocode\Cin7\Sync\Synchroniser;
use Ipsocode\Cin7\Tests\TestCase;

/**
 * The sync switched on, so the provider loads its migration, with the clock fixed at
 * 2026-10-03 12:00:00 and every wait recorded rather than slept.
 */
#[WithConfig('cin7.sync.enabled', true)]
abstract class SyncTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Sleep::fake();
        $this->travelTo(Carbon::parse('2026-10-03 12:00:00'));
    }

    protected function tearDown(): void
    {
        Sleep::fake(false);

        parent::tearDown();
    }

    protected function synchroniser(): Synchroniser
    {
        return $this->app->make(Synchroniser::class);
    }

    /**
     * @return array<string, mixed>
     */
    protected function customer(string $id, ?string $modified = '2026-10-01T10:00:00.123Z', string $name = 'ACME'): array
    {
        return ['ID' => $id, 'Name' => $name, 'LastModifiedOn' => $modified];
    }

    /**
     * @return array<string, mixed>
     */
    protected function sale(string $id, string $updated = '2026-10-01T10:00:00Z'): array
    {
        return ['SaleID' => $id, 'OrderNumber' => 'SO-' . $id, 'Updated' => $updated];
    }

    /**
     * @return array<string, mixed>
     */
    protected function purchase(string $id, string $updated = '2026-10-01T10:00:00Z', string $type = 'Simple Purchase'): array
    {
        return ['ID' => $id, 'OrderNumber' => 'PO-' . $id, 'LastUpdatedDate' => $updated, 'Type' => $type];
    }

    /**
     * The stored rows of a module, keyed by Cin7 identifier.
     *
     * @return Collection<string, Payload>
     */
    protected function stored(Module $module): Collection
    {
        return Payload::forModule($module)->orderBy('cin7_id')->get()->keyBy('cin7_id');
    }

    /**
     * The query string of every request the mock client answered, in order.
     *
     * @return list<array<string, mixed>>
     */
    protected function queries(MockClient $mock): array
    {
        return $mock->recorded()->map(fn (Response $response): array => $response->pendingRequest()->queryParameters())->values()->all();
    }

    /**
     * The path of every request the mock client answered, in order.
     *
     * @return list<string>
     */
    protected function endpoints(MockClient $mock): array
    {
        return $mock->recorded()->map(fn (Response $response): string => $response->request()->resolveEndpoint())->values()->all();
    }
}
