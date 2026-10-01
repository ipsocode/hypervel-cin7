<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

use Hypervel\Support\ServiceProvider;

/**
 * Registers the Cin7 connector into the Hypervel container.
 *
 * @see docs/configuration.md
 */
class Cin7ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/cin7.php', 'cin7');

        // One connector per worker, shared across coroutines: it holds only readonly scalars.
        $this->app->singleton(Cin7Connector::class, function () {
            // The casts stop unset CIN7_* values (null) and string numbers from a TypeError
            // on resolve; a null store stays null.
            $store = config('cin7.rate_limit.store');

            return new Cin7Connector(
                (string) config('cin7.account_id'),
                (string) config('cin7.application_key'),
                (int) config('cin7.rate_limit.max', 60),
                (int) config('cin7.rate_limit.period', 60),
                $store === null ? null : (string) $store,
            );
        });
    }

    public function boot(): void
    {
        // Publishing only serves vendor:publish, so worker boots skip it.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/cin7.php' => config_path('cin7.php'),
            ], 'cin7-config');
        }
    }
}
