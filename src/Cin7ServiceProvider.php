<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

use Hypervel\Support\ServiceProvider;

/**
 * Registers the Cin7 connector into the Hypervel container.
 *
 * @see https://hypervel.org/docs/providers
 */
class Cin7ServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Merge the packaged defaults so the config is usable even before
        // the consumer publishes their own copy.
        $this->mergeConfigFrom(__DIR__ . '/../config/cin7.php', 'cin7');

        // A worker-shared singleton: the connector holds readonly scalars and
        // is never mutated per request, so there is no cross-coroutine state.
        $this->app->singleton(Cin7Connector::class, function () {
            // Cast rather than trust the config: with no CIN7_* provisioned
            // these are null, and an uncast null would TypeError on the first
            // container resolve — well before anyone tries to make a call.
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

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        // Publish registration only matters for `vendor:publish`; skip the
        // retained array on every worker boot, as the framework's own
        // providers do.
        if ($this->app->runningInConsole()) {
            // Expose the config for `php artisan vendor:publish --tag=cin7-config`.
            $this->publishes([
                __DIR__ . '/../config/cin7.php' => config_path('cin7.php'),
            ], 'cin7-config');
        }
    }
}
