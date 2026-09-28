<?php

declare(strict_types=1);

namespace Workbench\App\Providers;

use Hypervel\Support\ServiceProvider;
use Ipsocode\Cin7\Cin7Connector;
use Workbench\App\Services\CustomerDirectory;

/**
 * The Workbench application's own provider.
 *
 * This package ships no migrations, models or routes, so there is nothing to
 * load here. What it does own is the one thing a consuming application
 * actually does with this package: bind a service that takes the connector by
 * constructor injection. Registering it here rather than in a test is what
 * proves the container binding published by Cin7ServiceProvider is resolvable
 * from an application's own graph, not only from a direct `$app->make()`.
 */
class WorkbenchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bound as a singleton for the same reason the connector is: it holds
        // one readonly dependency and no per-request state, so a Swoole worker
        // can share a single instance across coroutines.
        $this->app->singleton(
            CustomerDirectory::class,
            fn ($app) => new CustomerDirectory($app->make(Cin7Connector::class)),
        );
    }
}
