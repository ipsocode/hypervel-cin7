<?php

declare(strict_types=1);

namespace Workbench\App\Providers;

use Hypervel\Support\ServiceProvider;
use Ipsocode\Cin7\Cin7Connector;
use Workbench\App\Services\CustomerDirectory;

/**
 * The Workbench application's provider, binding a service that takes the connector by
 * constructor injection.
 *
 * Binding it here proves Cin7ServiceProvider's singleton resolves from an application's own graph.
 *
 * @see docs/testing.md
 */
class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // A singleton like the connector: one readonly dependency, no per-request state.
        $this->app->singleton(
            CustomerDirectory::class,
            fn ($app) => new CustomerDirectory($app->make(Cin7Connector::class)),
        );
    }
}
