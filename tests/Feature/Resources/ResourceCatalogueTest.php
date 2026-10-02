<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Resources;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Data\Sale\SalePostPutData;
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
use Ipsocode\Cin7\Requests\Sale\DeleteSale;
use Ipsocode\Cin7\Requests\Sale\GetSale;
use Ipsocode\Cin7\Requests\Sale\PostSale;
use Ipsocode\Cin7\Requests\Sale\PutSale;
use Ipsocode\Cin7\Requests\SaleList\GetSaleList;
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

        $this->mock = Saloon::fake(array_fill(0, 16, MockResponse::make(Cin7Payloads::customerList())));
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
            'ref tax get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->get(),
                GetTax::class,
                Method::GET,
                '/ExternalApi/v2/ref/tax',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'ref tax paginate' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->paginate()->current(),
                GetTax::class,
                Method::GET,
                '/ExternalApi/v2/ref/tax',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'ref tax post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->post(['Name' => 'VAT']),
                PostTax::class,
                Method::POST,
                '/ExternalApi/v2/ref/tax',
                [],
                ['Name' => 'VAT'],
            ],
            'ref tax put' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->put(['ID' => 'guid-1', 'Name' => 'VAT']),
                PutTax::class,
                Method::PUT,
                '/ExternalApi/v2/ref/tax',
                [],
                ['ID' => 'guid-1', 'Name' => 'VAT'],
            ],
            'ref customer credits get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->customer()->credits()->get(['CustomerID' => 'guid-1', 'ShowUsedCredits' => true]),
                GetCustomerCredits::class,
                Method::GET,
                '/ExternalApi/v2/ref/customer/credits',
                ['CustomerID' => 'guid-1', 'ShowUsedCredits' => 'true', 'page' => 1, 'limit' => 100],
                null,
            ],
            'ref customer credits paginate' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->customer()->credits()->paginate()->current(),
                GetCustomerCredits::class,
                Method::GET,
                '/ExternalApi/v2/ref/customer/credits',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'sale get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->get('guid-1', ['CombineAdditionalCharges' => true]),
                GetSale::class,
                Method::GET,
                '/ExternalApi/v2/sale',
                ['ID' => 'guid-1', 'CombineAdditionalCharges' => 'true'],
                null,
            ],
            'sale post' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->post(['Customer' => 'ACME']),
                PostSale::class,
                Method::POST,
                '/ExternalApi/v2/sale',
                [],
                ['Customer' => 'ACME'],
            ],
            'sale put' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->put(['ID' => 'guid-1', 'Note' => 'Rush']),
                PutSale::class,
                Method::PUT,
                '/ExternalApi/v2/sale',
                [],
                ['ID' => 'guid-1', 'Note' => 'Rush'],
            ],
            'sale delete' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->delete('guid-1'),
                DeleteSale::class,
                Method::DELETE,
                '/ExternalApi/v2/sale',
                ['ID' => 'guid-1', 'Void' => 'false'],
                null,
            ],
            'sale delete with void' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->delete('guid-1', void: true),
                DeleteSale::class,
                Method::DELETE,
                '/ExternalApi/v2/sale',
                ['ID' => 'guid-1', 'Void' => 'true'],
                null,
            ],
            'saleList get' => [
                fn (Cin7Connector $cin7): mixed => $cin7->saleList()->get(['Status' => 'ORDERED']),
                GetSaleList::class,
                Method::GET,
                '/ExternalApi/v2/saleList',
                ['Status' => 'ORDERED', 'page' => 1, 'limit' => 100],
                null,
            ],
            'saleList paginate' => [
                fn (Cin7Connector $cin7): mixed => $cin7->saleList()->paginate()->current(),
                GetSaleList::class,
                Method::GET,
                '/ExternalApi/v2/saleList',
                ['page' => 1, 'limit' => 100],
                null,
            ],
            'sale post with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->post(SalePostPutData::from(['Customer' => 'ACME'])),
                PostSale::class,
                Method::POST,
                '/ExternalApi/v2/sale',
                [],
                ['Customer' => 'ACME'],
            ],
            'sale put with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->sale()->put(SalePostPutData::from(['ID' => 'guid-1', 'Note' => 'Rush'])),
                PutSale::class,
                Method::PUT,
                '/ExternalApi/v2/sale',
                [],
                ['ID' => 'guid-1', 'Note' => 'Rush'],
            ],
            'ref tax post with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->post(TaxData::from(['Name' => 'VAT'])),
                PostTax::class,
                Method::POST,
                '/ExternalApi/v2/ref/tax',
                [],
                ['Name' => 'VAT'],
            ],
            'ref tax put with data' => [
                fn (Cin7Connector $cin7): mixed => $cin7->ref()->tax()->put(TaxData::from(['ID' => 'guid-1', 'Name' => 'VAT'])),
                PutTax::class,
                Method::PUT,
                '/ExternalApi/v2/ref/tax',
                [],
                ['ID' => 'guid-1', 'Name' => 'VAT'],
            ],
        ];
    }
}
