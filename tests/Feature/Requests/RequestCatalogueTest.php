<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Requests;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Requests\Cin7Request;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\Customer\PutCustomer;
use Ipsocode\Cin7\Requests\Product\GetProduct;
use Ipsocode\Cin7\Requests\Product\PostProduct;
use Ipsocode\Cin7\Requests\Product\PutProduct;
use Ipsocode\Cin7\Requests\Ref\Customer\Credits\GetCustomerCredits;
use Ipsocode\Cin7\Requests\Ref\Tax\GetTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PostTax;
use Ipsocode\Cin7\Requests\Ref\Tax\PutTax;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use SplFileInfo;
use Workbench\App\Support\Cin7Payloads;

/**
 * One row per concrete request class, plus the folder/path and verb/name conventions
 * every class in `src/Requests/` is held to.
 *
 * @see docs/requests.md
 */
class RequestCatalogueTest extends TestCase
{
    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = Saloon::fake(array_fill(0, 10, MockResponse::make(Cin7Payloads::customerList())));
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
        return [
            GetCustomer::class => [
                GetCustomer::class,
                [],
                Method::GET,
                '/ExternalApi/v2/customer',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            PostCustomer::class => [
                PostCustomer::class,
                [['Name' => 'ACME']],
                Method::POST,
                '/ExternalApi/v2/customer',
                [],
                ['Name' => 'ACME'],
            ],
            PutCustomer::class => [
                PutCustomer::class,
                [['ID' => 'guid-1', 'Name' => 'ACME']],
                Method::PUT,
                '/ExternalApi/v2/customer',
                [],
                ['ID' => 'guid-1', 'Name' => 'ACME'],
            ],
            GetProduct::class => [
                GetProduct::class,
                [],
                Method::GET,
                '/ExternalApi/v2/product',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            PostProduct::class => [
                PostProduct::class,
                [['Name' => 'Widget']],
                Method::POST,
                '/ExternalApi/v2/product',
                [],
                ['Name' => 'Widget'],
            ],
            PutProduct::class => [
                PutProduct::class,
                [['ID' => 'guid-1', 'Name' => 'Widget']],
                Method::PUT,
                '/ExternalApi/v2/product',
                [],
                ['ID' => 'guid-1', 'Name' => 'Widget'],
            ],
            GetCustomerCredits::class => [
                GetCustomerCredits::class,
                [],
                Method::GET,
                '/ExternalApi/v2/ref/customer/credits',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            GetTax::class => [
                GetTax::class,
                [],
                Method::GET,
                '/ExternalApi/v2/ref/tax',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            PostTax::class => [
                PostTax::class,
                [['Name' => 'VAT']],
                Method::POST,
                '/ExternalApi/v2/ref/tax',
                [],
                ['Name' => 'VAT'],
            ],
            PutTax::class => [
                PutTax::class,
                [['ID' => 'guid-1', 'Name' => 'VAT']],
                Method::PUT,
                '/ExternalApi/v2/ref/tax',
                [],
                ['ID' => 'guid-1', 'Name' => 'VAT'],
            ],
        ];
    }

    public function testTheConcreteClassesAreExactlyTheProvidersClasses(): void
    {
        $this->assertSame(
            array_keys(self::requestProvider()),
            self::concreteRequestClasses(),
        );
    }

    public function testEachClassFolderMatchesItsResolvedEndpointPath(): void
    {
        foreach (self::concreteRequestClasses() as $class) {
            $request = new ReflectionClass($class)->newInstanceWithoutConstructor();
            $relativeNamespace = str_replace(['Ipsocode\Cin7\Requests\\', '\\' . new ReflectionClass($class)->getShortName()], '', $class);
            $folderAsPath = str_replace('\\', '/', $relativeNamespace);

            $this->assertSame(
                strtolower($request->resolveEndpoint()),
                strtolower($folderAsPath),
                $class,
            );
        }
    }

    public function testEachClassNameStartsWithItsVerb(): void
    {
        foreach (self::concreteRequestClasses() as $class) {
            $request = new ReflectionClass($class)->newInstanceWithoutConstructor();
            $shortName = new ReflectionClass($class)->getShortName();

            $this->assertStringStartsWith(ucfirst(strtolower($request->method()->value)), $shortName, $class);
        }
    }

    /**
     * Every non-abstract class under `src/Requests/`, sorted the way the provider lists them.
     *
     * @return list<class-string<Cin7Request>>
     */
    private static function concreteRequestClasses(): array
    {
        $root = __DIR__ . '/../../../src/Requests';
        $classes = [];

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $relative = str_replace($root . '/', '', $file->getPathname());
            $class = 'Ipsocode\Cin7\Requests\\' . str_replace(['/', '.php'], ['\\', ''], $relative);

            if (! class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            if ($reflection->isAbstract()) {
                continue;
            }

            $classes[] = $class;
        }

        sort($classes);

        return $classes;
    }
}
