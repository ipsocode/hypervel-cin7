<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Workbench;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Services\CustomerDirectory;
use Workbench\App\Support\Cin7Payloads;

/**
 * Proves the connector binding resolves as a constructor dependency of an application service.
 *
 * @see docs/testing.md
 */
class CustomerDirectoryTest extends TestCase
{
    public function testTheWorkbenchServiceReceivesThePackagesConnector(): void
    {
        $directory = $this->app->make(CustomerDirectory::class);

        $this->assertInstanceOf(CustomerDirectory::class, $directory);

        // The connector is a per-worker singleton shared by every service.
        $this->assertSame($this->connector(), $this->app->make(Cin7Connector::class));
    }

    public function testItIsBoundAsASingletonLikeTheConnector(): void
    {
        $this->assertSame(
            $this->app->make(CustomerDirectory::class),
            $this->app->make(CustomerDirectory::class),
        );
    }

    public function testListingCustomersGoesOutAsAPaginatedGet(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerList([
                Cin7Payloads::customer('a', 'ACME'),
                Cin7Payloads::customer('b', 'Globex'),
            ])),
        ]);

        $customers = $this->app->make(CustomerDirectory::class)->all();

        $this->assertSame(['ACME', 'Globex'], array_column($customers, 'Name'));

        $pending = $mock->lastPendingRequest();

        $this->assertSame(Method::GET, $pending->method());
        $this->assertSame(['page' => 1, 'limit' => 100], $pending->queryParameters());
        $this->assertSame('acct-test', $pending->headers()['api-auth-accountid']);
    }

    /**
     * A Total of 150 at the default limit of 100 is two pages, and `all()` fetches both.
     */
    public function testListingAllCustomersWalksEveryPage(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('a', 'ACME')], page: 1, total: 150)),
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('b', 'Globex')], page: 2, total: 150)),
        ]);

        $customers = $this->app->make(CustomerDirectory::class)->all();

        $this->assertSame(['ACME', 'Globex'], array_column($customers, 'Name'));
        $mock->assertSentCount(2);
    }

    public function testCallerFiltersReachTheQueryString(): void
    {
        $mock = Saloon::fake([MockResponse::make(Cin7Payloads::customerList())]);

        $customers = $this->app->make(CustomerDirectory::class)->all(['Name' => 'ACME', 'limit' => 5]);

        $this->assertSame([], $customers);
        $this->assertSame(
            ['Name' => 'ACME', 'limit' => 5, 'page' => 1],
            $mock->lastPendingRequest()->queryParameters(),
        );
    }

    public function testFindingOneCustomerSendsTheGuidAsAQueryParameter(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('guid-1', 'ACME')])),
        ]);

        $customer = $this->app->make(CustomerDirectory::class)->find('guid-1');

        $this->assertInstanceOf(CustomerData::class, $customer);
        $this->assertSame('ACME', $customer->Name);
        $this->assertSame('guid-1', $mock->lastPendingRequest()->queryParameters()['ID']);
    }

    public function testFindingAMissingCustomerReturnsNull(): void
    {
        Saloon::fake([MockResponse::make(Cin7Payloads::customerList())]);

        $this->assertNull($this->app->make(CustomerDirectory::class)->find('nope'));
    }

    public function testCreatingACustomerPostsAJsonBody(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('new', 'ACME')])),
        ]);

        $created = $this->app->make(CustomerDirectory::class)->create(CustomerData::from(['Name' => 'ACME']));

        $this->assertSame('new', $created->ID);

        $pending = $mock->lastPendingRequest();

        $this->assertSame(Method::POST, $pending->method());
        $this->assertSame(['Name' => 'ACME'], $pending->body());
        $this->assertSame([], $pending->queryParameters());
    }

    public function testUpdatingACustomerPutsTheGuidInTheBody(): void
    {
        $mock = Saloon::fake([
            MockResponse::make(Cin7Payloads::customerList([Cin7Payloads::customer('guid-2', 'ACME Ltd')])),
        ]);

        $updated = $this->app->make(CustomerDirectory::class)->update(
            'guid-2',
            CustomerData::from(['ID' => 'other', 'Name' => 'ACME Ltd']),
        );

        $this->assertSame('ACME Ltd', $updated->Name);

        $pending = $mock->lastPendingRequest();

        $this->assertSame(Method::PUT, $pending->method());
        $this->assertSame(['ID' => 'guid-2', 'Name' => 'ACME Ltd'], $pending->body());
    }
}
