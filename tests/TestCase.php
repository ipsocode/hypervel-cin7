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
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Requests\ListRecords;

/**
 * Base for everything that needs the Testbench application.
 *
 * The environment this boots into — the fake Cin7 credentials and the rate
 * limit and retry knobs — comes from phpunit.xml's `<env>` block, which mirrors
 * testbench.yaml. Nothing is set through `defineEnvironment()` on purpose:
 * config/cin7.php reads every one of those values through `env()`, and setting
 * the resulting config keys directly would leave that file's own wiring
 * untested. Tests that need a different value override the config key, which is
 * the path a consumer's published config takes too.
 */
abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;

    /**
     * The connector as an application resolves it — through the container,
     * built from config by Cin7ServiceProvider, rather than by hand.
     *
     * Tests that construct `new Cin7Connector(...)` directly do so deliberately,
     * to pin constructor arguments the config path cannot reach.
     */
    protected function connector(): Cin7Connector
    {
        return $this->app->make(Cin7Connector::class);
    }

    /**
     * A PendingRequest built the way the framework builds one.
     *
     * Rate limiting cannot be observed through a faked send — the manager
     * enforces limits only when no fake response matched — so the hooks that
     * take a PendingRequest have to be called directly, and that needs one
     * assembled by hand.
     */
    protected function pendingRequestFor(Cin7Connector $connector, ?Request $request = null): PendingRequest
    {
        return new PendingRequest(
            $connector,
            $request ?? new ListRecords(Endpoint::Customer),
            $this->app->make(CacheFactory::class),
            $this->app->make(RateLimiter::class),
        );
    }

    /**
     * The global mock client lives on the SaloonManager, which is a container
     * singleton and so dies with the test application anyway. Clearing it here
     * is belt-and-braces — and it means no individual test has to remember.
     */
    protected function tearDown(): void
    {
        Saloon::clearFake();

        parent::tearDown();
    }
}
