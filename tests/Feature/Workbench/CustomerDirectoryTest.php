<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Workbench;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Services\CustomerDirectory;
use Workbench\App\Support\Cin7Payloads;

/**
 * The Workbench application is what this package is tested against, so the seam
 * it models is worth a test of its own.
 *
 * Every other Feature test resolves the connector and hands it a request. An
 * application does neither: it type-hints a service of its own and lets the
 * container assemble the graph. That is the path exercised here — nothing about
 * the requests themselves, which the request tests already cover, but the
 * binding `Cin7ServiceProvider` publishes being usable as a constructor
 * dependency rather than only through `$app->make()`.
 */
class CustomerDirectoryTest extends TestCase
{
    public function testTheWorkbenchServiceReceivesThePackagesConnector(): void
    {
        $directory = $this->app->make(CustomerDirectory::class);

        $this->assertInstanceOf(CustomerDirectory::class, $directory);

        // The same instance the container hands anyone else — a second
        // connector would mean a second rate limit window.
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
     * The bug this fixes: `all()` used to send one request and hand back
     * whatever that single page carried, silently dropping everything after it.
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

        $this->assertSame('ACME', $customer['Name']);
        $this->assertSame('guid-1', $mock->lastPendingRequest()->queryParameters()['ID']);
    }

    public function testFindingAMissingCustomerReturnsNull(): void
    {
        Saloon::fake([MockResponse::make(Cin7Payloads::customerList())]);

        $this->assertNull($this->app->make(CustomerDirectory::class)->find('nope'));
    }

    public function testCreatingACustomerPostsAJsonBody(): void
    {
        $mock = Saloon::fake([MockResponse::make(Cin7Payloads::customer('new', 'ACME'))]);

        $created = $this->app->make(CustomerDirectory::class)->create(['Name' => 'ACME']);

        $this->assertSame('new', $created['ID']);

        $pending = $mock->lastPendingRequest();

        $this->assertSame(Method::POST, $pending->method());
        $this->assertSame(['Name' => 'ACME'], $pending->body());
        $this->assertSame([], $pending->queryParameters());
    }

    public function testUpdatingACustomerPutsTheGuidInTheBody(): void
    {
        $mock = Saloon::fake([MockResponse::make(Cin7Payloads::customer('guid-2', 'ACME Ltd'))]);

        $this->app->make(CustomerDirectory::class)->update('guid-2', ['Name' => 'ACME Ltd']);

        $pending = $mock->lastPendingRequest();

        $this->assertSame(Method::PUT, $pending->method());
        $this->assertSame(['Name' => 'ACME Ltd', 'ID' => 'guid-2'], $pending->body());
    }
}
