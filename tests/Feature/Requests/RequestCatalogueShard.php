<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Requests;

use Closure;
use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Ipsocode\Cin7\Requests\Cin7Request;
use Ipsocode\Cin7\Testing\Cin7Fake;
use Ipsocode\Cin7\Tests\Catalogue;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The rows of one shard of the `requests` catalog kind. ParaTest hands a worker whole files,
 * so the rows are spread over the `RequestCatalogueShard*Test` classes instead of sitting in one
 * file that bounds the parallel run; the shards together hold every row once.
 *
 * @see src/docs/requests.md
 */
abstract class RequestCatalogueShard extends TestCase
{
    /**
     * Which of the `Catalogue::SHARDS` shards this class runs.
     */
    protected const int SHARD = 0;

    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = Saloon::fake(array_fill(0, 16, Cin7Fake::list('CustomerList')));
    }

    /**
     * @param class-string<Cin7Request> $class
     * @param list<mixed> $args
     * @param array<string, mixed> $query
     * @param null|array<string, mixed> $body
     */
    #[DataProvider('requestProvider')]
    public function testTheRequestBuildsAsDocumented(
        string $class,
        array $args,
        Method $method,
        string $path,
        array $query,
        ?array $body,
    ): void {
        // A data object needs the booted container, which a provider runs before; its row
        // passes a closure instead.
        $args = array_map(static fn (mixed $arg): mixed => $arg instanceof Closure ? $arg() : $arg, $args);

        $this->connector()->send(new $class(...$args));

        $pending = $this->mock->lastPendingRequest();

        $this->assertNotNull($pending);
        $this->assertSame($method, $pending->method());
        $this->assertSame($path, $pending->uri()->getPath());
        $this->assertSame($query, $pending->queryParameters());
        $this->assertSame($body, $pending->body());
    }

    /**
     * @return array<string, array{string, list<mixed>, Method, string, array<string, mixed>, null|array<string, mixed>}>
     */
    public static function requestProvider(): array
    {
        return Catalogue::shard('requests', static::SHARD);
    }
}
