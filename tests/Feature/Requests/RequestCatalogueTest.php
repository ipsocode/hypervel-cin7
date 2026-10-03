<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Requests;

use Closure;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use InvalidArgumentException;
use Ipsocode\Cin7\Data\Product\ProductPostData;
use Ipsocode\Cin7\Data\Product\ProductPutData;
use Ipsocode\Cin7\Data\Sale\SalePutData;
use Ipsocode\Cin7\Requests\Cin7Request;
use Ipsocode\Cin7\Requests\ListRequest;
use Ipsocode\Cin7\Requests\Product\PostProduct;
use Ipsocode\Cin7\Requests\Product\PutProduct;
use Ipsocode\Cin7\Requests\Sale\PostSale;
use Ipsocode\Cin7\Requests\Sale\PutSale;
use Ipsocode\Cin7\Testing\Cin7Fake;
use Ipsocode\Cin7\Tests\Catalogue;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use SplFileInfo;
use Workbench\App\Support\Cin7Payloads;

/**
 * One row per concrete request class, from the per-path files under `tests/Fixtures/Catalogue/`,
 * plus the folder/path and verb/name conventions every class in `src/Requests/` is held to.
 *
 * @see docs/requests.md
 */
class RequestCatalogueTest extends TestCase
{
    /**
     * The paths whose folder and classes are named after the model they serve: `moneyOperation`
     * serves the Money Task, which also names the reference's group of money endpoints.
     */
    private const array PATHS_NAMED_AFTER_THEIR_MODEL = ['moneyOperation' => 'MoneyTask'];

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
        return Catalogue::rows('requests');
    }

    /**
     * @param class-string $class
     * @param array<string, mixed> $body
     * @param array<string, mixed> $sent
     */
    #[DataProvider('omittedFieldsProvider')]
    public function testReadOnlyAndOtherMethodFieldsAreLeftOutOfTheBody(string $class, array $body, array $sent): void
    {
        $this->connector()->send(new $class($body));

        $this->assertSame($sent, $this->mock->lastPendingRequest()?->body());
    }

    /**
     * @return array<string, array{class-string, array<string, mixed>, array<string, mixed>}>
     */
    public static function omittedFieldsProvider(): array
    {
        return Catalogue::rows('omitted');
    }

    public function testPutSaleLeavesThePostOnlySaleTypeOutOfTheBody(): void
    {
        $this->connector()->send(new PutSale(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'SaleType' => 'Advanced']));
        $this->assertSame(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'], $this->mock->lastPendingRequest()?->body());

        $this->connector()->send(new PutSale(SalePutData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'CustomerID' => '6c18f8e9-90e1-418f-aebc-1219e67e4b9c', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1, 'SaleType' => 'Advanced'])));
        $this->assertSame(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'CustomerID' => '6c18f8e9-90e1-418f-aebc-1219e67e4b9c', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1.0], $this->mock->lastPendingRequest()?->body());
    }

    public function testPostSaleKeepsTheSaleType(): void
    {
        $this->connector()->send(new PostSale(['Customer' => 'ACME', 'SaleType' => 'Simple']));
        $this->assertSame(['Customer' => 'ACME', 'SaleType' => 'Simple'], $this->mock->lastPendingRequest()?->body());
    }

    public function testPostProductLeavesTheIgnoredIdOutOfTheBody(): void
    {
        $this->connector()->send(new PostProduct(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1', 'SKU' => 'Bread']));
        $this->assertSame(['SKU' => 'Bread'], $this->mock->lastPendingRequest()?->body());

        // ProductPostData has no ID, so from() drops it.
        $this->connector()->send(new PostProduct(ProductPostData::from(['ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'] + Cin7Payloads::load('product', 'post.request'))));
        $this->assertArrayNotHasKey('ID', $this->mock->lastPendingRequest()?->body());
    }

    /**
     * @param array<string, mixed>|Closure(): ProductPutData $body
     */
    #[DataProvider('productPutWithoutIdProvider')]
    public function testPutProductNeedsTheId(array|Closure $body): void
    {
        $this->expectException(InvalidArgumentException::class);

        new PutProduct($body instanceof Closure ? $body() : $body);
    }

    /**
     * @return array<string, array{array<string, mixed>|Closure(): ProductPutData}>
     */
    public static function productPutWithoutIdProvider(): array
    {
        return [
            'array without ID' => [['Name' => 'Widget']],
            'array with empty ID' => [['ID' => '', 'Name' => 'Widget']],
            'array with null ID' => [['ID' => null]],
            'data with empty ID' => [fn (): ProductPutData => ProductPutData::from(['ID' => ''] + Cin7Payloads::load('product', 'put.request'))],
        ];
    }

    public function testTheConcreteClassesAreExactlyTheProvidersClasses(): void
    {
        $classes = array_keys(array_filter(self::requestProvider(), static fn (string $key): bool => ! str_ends_with($key, ' with data'), ARRAY_FILTER_USE_KEY));
        sort($classes);

        $this->assertSame(self::concreteRequestClasses(), $classes);
    }

    public function testEachClassFolderMatchesItsResolvedEndpointPath(): void
    {
        foreach (self::concreteRequestClasses() as $class) {
            $request = new ReflectionClass($class)->newInstanceWithoutConstructor();
            $relativeNamespace = str_replace(['Ipsocode\Cin7\Requests\\', '\\' . new ReflectionClass($class)->getShortName()], '', $class);
            $folderAsPath = str_replace('\\', '/', $relativeNamespace);
            $path = self::PATHS_NAMED_AFTER_THEIR_MODEL[$request->resolveEndpoint()] ?? $request->resolveEndpoint();

            // A hyphenated segment is one PascalCase folder: `advanced-purchase/put-away` is
            // AdvancedPurchase/PutAway.
            $this->assertSame(
                strtolower(str_replace('-', '', $path)),
                strtolower($folderAsPath),
                $class,
            );
        }
    }

    /**
     * A list request names the data class of one item, which `ListRequest::createDtoFromResponse()`
     * builds its `dto()` from, so it declares neither that method nor a missing `$item`.
     */
    public function testEveryListRequestNamesItsItemClass(): void
    {
        $lists = array_filter(self::concreteRequestClasses(), static fn (string $class): bool => is_subclass_of($class, ListRequest::class));

        $this->assertNotEmpty($lists);

        foreach ($lists as $class) {
            $reflection = new ReflectionClass($class);
            $item = $reflection->getProperty('item')->getDefaultValue();

            $this->assertTrue(is_string($item) && is_subclass_of($item, Data::class) && is_subclass_of($item, WithResponse::class), "{$class}::\$item must name a data class that implements WithResponse.");
            $this->assertSame(ListRequest::class, $reflection->getMethod('createDtoFromResponse')->getDeclaringClass()->getName(), $class);
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
