<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Resources;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Customer\PostCustomer;
use Ipsocode\Cin7\Requests\Customer\PutCustomer;
use Ipsocode\Cin7\Requests\Product\GetProduct;
use Ipsocode\Cin7\Requests\Product\PostProduct;
use Ipsocode\Cin7\Requests\Product\PutProduct;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Workbench\App\Support\Cin7Payloads;

/**
 * One row per resource method, asserting on the request it builds and the PendingRequest
 * it sends.
 *
 * @see docs/resources.md
 */
class ResourceCatalogueTest extends TestCase
{
    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = Saloon::fake(array_fill(0, 8, MockResponse::make(Cin7Payloads::customerList())));
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
        return [
            'customer get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->customer()->get(),
                GetCustomer::class,
                Method::GET,
                '/ExternalApi/v2/customer',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'customer paginate' => [
                fn (Cin7Connector $cin7): mixed => $cin7->customer()->paginate()->current(),
                GetCustomer::class,
                Method::GET,
                '/ExternalApi/v2/customer',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'customer post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->customer()->post(['Name' => 'ACME']),
                PostCustomer::class,
                Method::POST,
                '/ExternalApi/v2/customer',
                [],
                ['Name' => 'ACME'],
            ],
            'customer put' => [
                fn (Cin7Connector $cin7): mixed => $cin7->customer()->put(['ID' => 'guid-1', 'Name' => 'ACME']),
                PutCustomer::class,
                Method::PUT,
                '/ExternalApi/v2/customer',
                [],
                ['ID' => 'guid-1', 'Name' => 'ACME'],
            ],
            'product get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->product()->get(),
                GetProduct::class,
                Method::GET,
                '/ExternalApi/v2/product',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'product paginate' => [
                fn (Cin7Connector $cin7): mixed => $cin7->product()->paginate()->current(),
                GetProduct::class,
                Method::GET,
                '/ExternalApi/v2/product',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'product post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->product()->post(['Name' => 'Widget']),
                PostProduct::class,
                Method::POST,
                '/ExternalApi/v2/product',
                [],
                ['Name' => 'Widget'],
            ],
            'product put' => [
                fn (Cin7Connector $cin7): mixed => $cin7->product()->put(['ID' => 'guid-1', 'Name' => 'Widget']),
                PutProduct::class,
                Method::PUT,
                '/ExternalApi/v2/product',
                [],
                ['ID' => 'guid-1', 'Name' => 'Widget'],
            ],
        ];
    }
}
