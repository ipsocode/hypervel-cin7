<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use Hypervel\Console\Scheduling\CallbackEvent;
use Hypervel\Console\Scheduling\Event;
use Hypervel\Console\Scheduling\Schedule;
use Hypervel\Support\Facades\Artisan;
use Hypervel\Support\Facades\Queue;
use Hypervel\Support\Facades\Schema;
use Hypervel\Support\ServiceProvider;
use Ipsocode\Cin7\Cin7ServiceProvider;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\Pull;

/**
 * What the provider wires when the sync is on: the migration, the merged config and the
 * schedule. Off, the default, is in ServiceProviderTest.
 *
 * @see docs/sync.md
 */
class SyncProviderTest extends SyncTestCase
{
    public function testTheMigrationIsLoaded(): void
    {
        $this->assertContains(
            realpath(__DIR__ . '/../../../database/migrations'),
            array_map(realpath(...), $this->app->make('migrator')->paths()),
        );
        $this->assertTrue(Schema::hasTable('cin7_sync_payloads'));
    }

    public function testTheMigrationIsPublishable(): void
    {
        $paths = ServiceProvider::pathsToPublish(Cin7ServiceProvider::class, 'cin7-migrations');

        $this->assertSame([realpath(__DIR__ . '/../../../database/migrations')], array_map(realpath(...), array_keys($paths)));
        $this->assertSame([database_path('migrations')], array_values($paths));
    }

    /**
     * #[WithConfig] sets only `enabled`; the merge keeps the package's other sync keys.
     */
    public function testTheSyncConfigMergesOneLevelDeep(): void
    {
        $this->assertTrue(config('cin7.sync.enabled'));
        $this->assertSame(500, config('cin7.sync.limit'));
        $this->assertSame('*', config('cin7.sync.modules'));
    }

    public function testAboutReportsTheSyncSchedule(): void
    {
        $this->assertSame(['on', '0 * * * *', '0 2 * * 0'], $this->about());

        $this->app->get('config')->set('cin7.sync.cron', null);
        $this->app->get('config')->set('cin7.sync.full', null);

        $this->assertSame(['on', 'not scheduled', 'not scheduled'], $this->about());
    }

    public function testTheCommonPullAndTheFullPullAreScheduledOnOneServer(): void
    {
        $events = $this->scheduled();

        $this->assertSame(['cin7:sync' => '0 * * * *', 'cin7:sync:full' => '0 2 * * 0'], array_map(
            fn (Event $event): string => $event->expression,
            $events,
        ));

        foreach ($events as $event) {
            $this->assertInstanceOf(CallbackEvent::class, $event);
            $this->assertTrue($event->onOneServer);
        }
    }

    public function testEachExceptionIsScheduledAsWell(): void
    {
        $this->app->get('config')->set('cin7.sync.exceptions', ['saleList' => '*/5 * * * *', 'customer' => '*/15 * * * *']);

        $this->assertSame(
            ['cin7:sync' => '0 * * * *', 'cin7:sync:saleList' => '*/5 * * * *', 'cin7:sync:customer' => '*/15 * * * *', 'cin7:sync:full' => '0 2 * * 0'],
            array_map(fn (Event $event): string => $event->expression, $this->scheduled()),
        );
    }

    public function testANullTimeSchedulesNothing(): void
    {
        $this->app->get('config')->set('cin7.sync.cron', null);
        $this->app->get('config')->set('cin7.sync.full', null);

        $this->assertSame([], $this->scheduled());
    }

    public function testTheCommonPullLeavesTheReferenceBooksToTheFullPull(): void
    {
        $this->app->get('config')->set('cin7.sync.modules', ['ref/account', 'customer', 'sale']);
        Queue::fake();

        $events = $this->scheduled();
        $events['cin7:sync']->run($this->app);
        $events['cin7:sync:full']->run($this->app);

        Queue::assertPushed(Pull::class, fn (Pull $pull): bool => $pull->modules === [Module::Customer, Module::Sale] && ! $pull->full);
        Queue::assertPushed(Pull::class, fn (Pull $pull): bool => $pull->modules === [Module::Account, Module::Customer, Module::Sale] && $pull->full);
    }

    public function testOnlyReferenceBooksScheduleNoCommonPull(): void
    {
        $this->app->get('config')->set('cin7.sync.modules', ['ref/account', 'ref/tax']);

        $this->assertSame(['cin7:sync:full'], array_keys($this->scheduled()));
    }

    public function testTheScheduledPullDispatchesTheJob(): void
    {
        $this->app->get('config')->set('cin7.sync.modules', ['customer']);
        Queue::fake();

        $this->scheduled()['cin7:sync']->run($this->app);

        Queue::assertPushed(Pull::class, fn (Pull $pull): bool => $pull->modules === [Module::Customer] && ! $pull->full);
    }

    /**
     * The sync's entries in a schedule built afresh from the current config, keyed by name.
     *
     * @return array<string, Event>
     */
    private function scheduled(): array
    {
        $this->app->forgetInstance(Schedule::class);

        $events = [];

        foreach ($this->app->make(Schedule::class)->events() as $event) {
            if (str_starts_with((string) $event->description, 'cin7:sync')) {
                $events[(string) $event->description] = $event;
            }
        }

        return $events;
    }

    /**
     * The sync's lines of `about --json`.
     *
     * @return list<string>
     */
    private function about(): array
    {
        Artisan::call('about', ['--json' => true]);
        $about = json_decode(Artisan::output(), true)['cin7'];

        return [$about['sync'], $about['sync_pull'], $about['sync_full_pull']];
    }
}
