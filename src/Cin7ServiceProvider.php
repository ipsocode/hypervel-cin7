<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

use Composer\InstalledVersions;
use Hypervel\Console\Scheduling\Schedule;
use Hypervel\Contracts\Foundation\Application;
use Hypervel\Foundation\Console\AboutCommand;
use Hypervel\Support\ServiceProvider;
use InvalidArgumentException;
use Ipsocode\Cin7\Console\CheckCommand;
use Ipsocode\Cin7\Console\SyncCommand;
use Ipsocode\Cin7\Sync\Pull;
use Ipsocode\Cin7\Sync\SyncConfig;

/**
 * Registers the Cin7 connector into the Hypervel container, and the sync when it is on.
 *
 * @see src/docs/configuration.md
 * @see src/docs/sync.md
 */
class Cin7ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/cin7.php', 'cin7');

        // Injecting the connector by type resolves the default connection. Not a singleton of
        // its own: Cin7Manager is auto-singletoned, holds one readonly connector per connection
        // and shares it across coroutines, so its map is the one place an instance lives.
        $this->app->bind(Cin7Connector::class, fn (Application $app) => $app->make(Cin7Manager::class)->connection());
    }

    public function boot(): void
    {
        // Publishing, commands and the schedule only serve the console, so worker boots skip them.
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/cin7.php' => config_path('cin7.php'),
            ], 'cin7-config');

            $this->publishesMigrations([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
            ], 'cin7-migrations');

            $this->commands([CheckCommand::class, SyncCommand::class]);

            $this->registerAbout();
        }

        // Off, the sync creates no table and schedules nothing.
        if (! SyncConfig::enabled()) {
            return;
        }

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->callAfterResolving(Schedule::class, $this->scheduleSync(...));
        }
    }

    /**
     * Schedule the modules that read only what changed at `sync.cron`, each exception at its own time as
     * well, and every module, the reference books included, in the full pull at `sync.full`; a
     * null time schedules nothing. Each runs on one server.
     */
    protected function scheduleSync(Schedule $schedule): void
    {
        $modules = SyncConfig::modules();
        $scheduled = SyncConfig::scheduled();
        $entries = [];

        if (($cron = SyncConfig::cron()) !== null && $scheduled !== []) {
            $entries['cin7:sync'] = [new Pull($scheduled), $cron];
        }

        foreach (SyncConfig::exceptions() as [$module, $expression]) {
            $entries['cin7:sync:' . $module->value] = [new Pull([$module]), $expression];
        }

        if (($full = SyncConfig::full()) !== null) {
            $entries['cin7:sync:full'] = [new Pull($modules, full: true), $full];
        }

        foreach ($entries as $name => [$pull, $expression]) {
            $schedule->job($pull)->cron($expression)->name($name)->onOneServer();
        }
    }

    /**
     * Add the package's section of `php artisan about`, read when the command runs. It names
     * neither credential.
     */
    protected function registerAbout(): void
    {
        AboutCommand::add('Cin7', function (): array {
            // A diagnostic must still describe an app whose credentials are not set yet.
            try {
                $cin7 = $this->app->make(Cin7Manager::class)->connection();
                $window = $cin7->rateLimitWindow();
                $rateLimit = $window === null ? 'off' : "{$window[0]} calls per {$window[1]}s";
                $limiterStore = $cin7->limiterStore();
            } catch (InvalidArgumentException) {
                $rateLimit = $limiterStore = 'not configured';
            }

            $sync = SyncConfig::enabled();

            return [
                'Version' => InstalledVersions::getPrettyVersion('ipsocode/hypervel-cin7'),
                'Rate limit' => $rateLimit,
                'Limiter store' => $limiterStore,
                'Sync' => $sync ? 'on' : 'off',
                'Sync pull' => $sync ? (SyncConfig::cron() ?? 'not scheduled') : '-',
                'Sync full pull' => $sync ? (SyncConfig::full() ?? 'not scheduled') : '-',
            ];
        });
    }

    /**
     * `rate_limit`, `retry` and `sync` are merged one level deep, so a published file that sets
     * one of their keys keeps the package's others.
     *
     * @return array<int, string>
     */
    protected function mergeableOptions(string $name): array
    {
        return match ($name) {
            'cin7' => ['rate_limit', 'retry', 'sync'],
            default => [],
        };
    }
}
