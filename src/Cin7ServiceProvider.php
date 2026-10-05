<?php

declare(strict_types=1);

namespace Ipsocode\Cin7;

use Composer\InstalledVersions;
use Hypervel\Console\Scheduling\Schedule;
use Hypervel\Foundation\Console\AboutCommand;
use Hypervel\Support\ServiceProvider;
use Ipsocode\Cin7\Console\SyncCommand;
use Ipsocode\Cin7\Sync\Pull;
use Ipsocode\Cin7\Sync\SyncConfig;

/**
 * Registers the Cin7 connector into the Hypervel container, and the sync when it is on.
 *
 * @see docs/configuration.md
 * @see docs/sync.md
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
                (int) (config('cin7.rate_limit.cooldown') ?? Cin7Connector::THROTTLE_COOLDOWN),
            );
        });
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

            $this->commands([SyncCommand::class]);

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
     * The modules that read only what changed at `sync.cron`, each exception at its own time as
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
     * The package's section of `php artisan about`, read when the command runs. It names
     * neither credential.
     */
    protected function registerAbout(): void
    {
        AboutCommand::add('Cin7', static function (): array {
            $max = (int) config('cin7.rate_limit.max', 60);
            $period = (int) config('cin7.rate_limit.period', 60);
            $sync = SyncConfig::enabled();

            return [
                'Version' => InstalledVersions::getPrettyVersion('ipsocode/hypervel-cin7'),
                'Rate limit' => $max > 0 && $period > 0 ? "{$max} calls per {$period}s" : 'off',
                'Limiter store' => (string) (config('cin7.rate_limit.store') ?? 'default'),
                'Sync' => $sync ? 'on' : 'off',
                'Sync pull' => $sync ? (SyncConfig::cron() ?? 'not scheduled') : '-',
                'Sync full pull' => $sync ? (SyncConfig::full() ?? 'not scheduled') : '-',
            ];
        });
    }

    /**
     * `sync` is merged one level deep, so a published file that sets one of its keys keeps the
     * package's others.
     *
     * @return array<int, string>
     */
    protected function mergeableOptions(string $name): array
    {
        return match ($name) {
            'cin7' => ['sync'],
            default => [],
        };
    }
}
