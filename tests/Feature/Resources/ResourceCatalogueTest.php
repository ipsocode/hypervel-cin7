<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Resources;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Tests\Catalogue;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use SplFileInfo;
use Workbench\App\Support\Cin7Payloads;

/**
 * One row per resource method, from the per-path files under `tests/Fixtures/Catalogue/`,
 * asserting on the request it builds and the PendingRequest it sends.
 *
 * @see docs/resources.md
 */
class ResourceCatalogueTest extends TestCase
{
    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = Saloon::fake(array_fill(0, 20, MockResponse::make(Cin7Payloads::customerList())));
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
        return Catalogue::rows('resources');
    }

    /**
     * Each resource method that sends a request has a row keyed `<resource path> <method>`, or
     * that key and a variant (`… with data`): `sale payment put` for `Sale\PaymentResource::put()`.
     * Accessors, which return a resource, are covered by ConnectorResourcesTest.
     */
    public function testEveryResourceMethodHasARow(): void
    {
        $keys = array_keys(self::resourceProvider());

        foreach (self::resourceClasses() as $class) {
            $segments = explode('\\', substr($class, strlen('Ipsocode\Cin7\Resources\\'), -strlen('Resource')));
            $path = implode(' ', array_map(lcfirst(...), $segments));

            foreach (new ReflectionClass($class)->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $returns = $method->getReturnType();

                if ($method->getDeclaringClass()->getName() !== $class
                    || ($returns instanceof ReflectionNamedType && is_a($returns->getName(), BaseResource::class, true))) {
                    continue;
                }

                $key = $path . ' ' . $method->getName();
                $covered = array_filter($keys, static fn (string $row): bool => $row === $key || str_starts_with($row, $key . ' '));

                $this->assertNotEmpty($covered, "No resource row for {$class}::{$method->getName()}() ('{$key}').");
            }
        }
    }

    /**
     * Every class under `src/Resources/`.
     *
     * @return list<class-string<BaseResource>>
     */
    private static function resourceClasses(): array
    {
        $root = __DIR__ . '/../../../src/Resources';
        $classes = [];

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {
            if ($file->getExtension() === 'php') {
                $classes[] = 'Ipsocode\Cin7\Resources\\' . str_replace(['/', '.php'], ['\\', ''], substr($file->getPathname(), strlen($root) + 1));
            }
        }

        sort($classes);

        return $classes;
    }
}
