<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Resources;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Testing\Cin7Fake;
use Ipsocode\Cin7\Tests\Catalogue;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The rows of one shard of the `resources` catalogue kind. ParaTest hands a worker whole files,
 * so the rows are spread over the `ResourceCatalogueShard*Test` classes instead of sitting in one
 * file that bounds the parallel run; the shards together hold every row once.
 *
 * @see docs/resources.md
 */
abstract class ResourceCatalogueShard extends TestCase
{
    /**
     * Which of the `Catalogue::SHARDS` shards this class runs.
     */
    protected const int SHARD = 0;

    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = Saloon::fake(array_fill(0, 20, Cin7Fake::list('CustomerList')));
    }

    /**
     * @param callable(Cin7Connector): mixed $call
     * @param array<string, mixed> $query
     * @param null|array<string, mixed> $body
     */
    #[DataProvider('resourceProvider')]
    public function testTheResourceMethodBuildsItsRequest(
        callable $call,
        string $requestClass,
        Method $method,
        string $path,
        array $query,
        ?array $body,
    ): void {
        $call($this->connector());

        $pending = $this->mock->lastPendingRequest();

        $this->assertNotNull($pending);
        $this->assertInstanceOf($requestClass, $pending->request());
        $this->assertSame($method, $pending->method());
        $this->assertSame($path, $pending->uri()->getPath());
        $this->assertSame($query, $pending->queryParameters());
        $this->assertSame($body, $pending->body());
    }

    /**
     * @return array<string, array{callable(Cin7Connector): mixed, string, Method, string, array<string, mixed>, null|array<string, mixed>}>
     */
    public static function resourceProvider(): array
    {
        return Catalogue::shard('resources', static::SHARD);
    }
}
