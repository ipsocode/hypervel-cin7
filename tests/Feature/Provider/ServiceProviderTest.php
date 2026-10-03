<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Provider;

use Hypervel\Console\Scheduling\Event;
use Hypervel\Console\Scheduling\Schedule;
use Hypervel\Support\Facades\Artisan;
use Hypervel\Support\Facades\Schema;
use Hypervel\Support\ServiceProvider;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Cin7ServiceProvider;
use Ipsocode\Cin7\Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testItMergesThePackagedConfigDefaults(): void
    {
        $this->assertSame(60, config('cin7.rate_limit.max'));
        $this->assertSame(60, config('cin7.rate_limit.period'));
        $this->assertSame(4, config('cin7.retry.times'));
        $this->assertSame(5000, config('cin7.retry.delay_ms'));
    }

    /**
     * The values come from phpunit.xml's CIN7_* `<env>` entries, which mirror testbench.yaml.
     */
    public function testTheCredentialsComeFromTheEnvironment(): void
    {
        $this->assertSame('acct-test', config('cin7.account_id'));
        $this->assertSame('key-test', config('cin7.application_key'));
    }

    public function testTheConnectorResolvesAsAMemoizedSingleton(): void
    {
        $connector = $this->connector();

        $this->assertInstanceOf(Cin7Connector::class, $connector);
        $this->assertSame($connector, $this->connector());
    }

    public function testTheConnectorIsBuiltFromConfig(): void
    {
        $connector = $this->connector();

        $this->assertSame(
            'https://inventory.dearsystems.com/ExternalApi/v2/',
            $connector->resolveBaseUrl(),
        );
        $this->assertSame('acct-test', $connector->headers()['api-auth-accountid']);
        $this->assertSame('key-test', $connector->headers()['api-auth-applicationkey']);
        $this->assertSame('application/json', $connector->headers()['Content-Type']);
    }

    public function testMissingCredentialsDoNotBreakContainerResolution(): void
    {
        $this->app->get('config')->set('cin7.account_id', null);
        $this->app->get('config')->set('cin7.application_key', null);
        $this->app->forgetInstance(Cin7Connector::class);

        $connector = $this->connector();

        $this->assertSame('', $connector->headers()['api-auth-accountid']);
        $this->assertSame('', $connector->headers()['api-auth-applicationkey']);
    }

    public function testTheRateLimitConfigIsCastRatherThanTrusted(): void
    {
        $this->app->get('config')->set('cin7.rate_limit.max', '30');
        $this->app->get('config')->set('cin7.rate_limit.period', '15');
        $this->app->forgetInstance(Cin7Connector::class);

        $policies = $this->connector()->resolveRateLimitPolicies(
            $this->pendingRequestFor($this->connector()),
        );

        $this->assertCount(1, $policies);
        $this->assertSame(30, $policies[0]->maxAttempts);
        $this->assertSame(15, $policies[0]->decaySeconds);
    }

    public function testPublishesIsGuardedOutsideConsole(): void
    {
        ServiceProvider::flushState();
        $this->app->setRunningInConsole(false);

        (new Cin7ServiceProvider($this->app))->boot();

        $this->assertSame(
            [],
            ServiceProvider::pathsToPublish(Cin7ServiceProvider::class, 'cin7-config'),
        );
    }

    public function testTheConfigIsPublishable(): void
    {
        $paths = ServiceProvider::pathsToPublish(Cin7ServiceProvider::class, 'cin7-config');

        $this->assertNotEmpty($paths);
        $this->assertSame(
            [realpath(__DIR__ . '/../../../config/cin7.php')],
            array_map(realpath(...), array_keys($paths)),
        );
    }

    public function testTheConfigPublishesToTheApplicationConfigPath(): void
    {
        $paths = ServiceProvider::pathsToPublish(Cin7ServiceProvider::class, 'cin7-config');

        $this->assertSame([config_path('cin7.php')], array_values($paths));
    }

    /**
     * The sync is off by default: no migration, no table, nothing scheduled.
     */
    public function testTheSyncIsOffByDefault(): void
    {
        $this->assertFalse(config('cin7.sync.enabled'));
        $this->assertNotContains(
            realpath(__DIR__ . '/../../../database/migrations'),
            array_map(realpath(...), $this->app->make('migrator')->paths()),
        );
        $this->assertFalse(Schema::hasTable('cin7_sync_payloads'));
        $this->assertSame([], array_filter(
            $this->app->make(Schedule::class)->events(),
            fn (Event $event): bool => str_starts_with((string) $event->description, 'cin7:'),
        ));
    }

    public function testTheSyncCommandRefusesWhileTheSyncIsOff(): void
    {
        $this->artisan('cin7:sync')
            ->expectsOutputToContain('The Cin7 sync is off')
            ->assertExitCode(1);
    }

    public function testTheConnectorNamesItsAccount(): void
    {
        $this->assertSame('acct-test', $this->connector()->accountId());
    }

    public function testAboutReportsTheConnectorAndTheSyncWithoutTheCredentials(): void
    {
        $this->artisan('about')
            ->expectsOutputToContain('Cin7')
            ->expectsOutputToContain('60 calls per 60s')
            ->doesntExpectOutputToContain('acct-test')
            ->doesntExpectOutputToContain('key-test')
            ->assertSuccessful();
    }

    public function testAboutSaysWhenTheRateLimitIsOff(): void
    {
        $this->app->get('config')->set('cin7.rate_limit.max', 0);
        $this->app->get('config')->set('cin7.rate_limit.store', 'redis');

        Artisan::call('about', ['--json' => true]);
        $about = json_decode(Artisan::output(), true)['cin7'];

        $this->assertSame(['off', 'redis', 'off', '-', '-'], [$about['rate_limit'], $about['limiter_store'], $about['sync'], $about['sync_pull'], $about['sync_full_pull']]);
    }
}
