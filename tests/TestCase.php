<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests;

use Hypervel\Contracts\Cache\Factory as CacheFactory;
use Hypervel\RateLimiter\RateLimiter;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\PendingRequest;
use Hypervel\Saloon\Http\Request;
use Hypervel\Testbench\Concerns\WithWorkbench;
use Hypervel\Testbench\TestCase as BaseTestCase;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;

/**
 * Base for tests that boot the Testbench application, whose environment comes only from
 * phpunit.xml's `<env>`.
 *
 * @see docs/testing.md
 */
abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;

    /**
     * The connector as an application resolves it, through the container.
     */
    protected function connector(): Cin7Connector
    {
        return $this->app->make(Cin7Connector::class);
    }

    /**
     * A PendingRequest built the way the framework builds one, for driving the rate-limit
     * hooks directly.
     */
    protected function pendingRequestFor(Cin7Connector $connector, ?Request $request = null): PendingRequest
    {
        return new PendingRequest(
            $connector,
            $request ?? new GetCustomer,
            $this->app->make(CacheFactory::class),
            $this->app->make(RateLimiter::class),
        );
    }

    protected function tearDown(): void
    {
        Saloon::clearFake();

        parent::tearDown();
    }
}
