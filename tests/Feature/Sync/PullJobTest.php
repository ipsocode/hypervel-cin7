<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use Hypervel\Saloon\Exceptions\Request\RequestException;
use Hypervel\Saloon\Facades\Saloon;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\Pull;
use Ipsocode\Cin7\Sync\SyncFailed;
use Ipsocode\Cin7\Sync\Synchroniser;
use Ipsocode\Cin7\Testing\Cin7Fake;

/**
 * The queued job the schedule dispatches.
 *
 * @see docs/sync.md
 */
class PullJobTest extends SyncTestCase
{
    public function testItPullsItsModules(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList', [$this->customer('c-1')])]);

        Pull::dispatch([Module::Customer]);

        $this->assertSame(['c-1'], $this->stored(Module::Customer)->keys()->all());
    }

    public function testAFailedModuleFailsTheJobOnceTheOthersHaveRun(): void
    {
        Saloon::fake([
            Cin7Fake::error('Something went wrong'),
            Cin7Fake::list('SupplierList', [['ID' => 's-1', 'LastModifiedOn' => '2026-10-01T10:00:00Z']]),
        ]);

        try {
            new Pull([Module::Customer, Module::Supplier])->handle($this->app->make(Synchroniser::class));
            $this->fail('The job did not fail.');
        } catch (SyncFailed $exception) {
            $this->assertStringStartsWith('The Cin7 sync failed for customer (', $exception->getMessage());
            $this->assertInstanceOf(RequestException::class, $exception->getPrevious());
        }

        $this->assertSame(['s-1'], $this->stored(Module::Supplier)->keys()->all());
    }

    public function testItsQueueTimeoutAndTries(): void
    {
        $this->app->get('config')->set('cin7.sync.queue', 'cin7');
        $this->app->get('config')->set('cin7.sync.timeout', 600);

        $pull = new Pull([Module::Customer]);

        $this->assertSame('cin7', $pull->queue);
        $this->assertSame(600, $pull->timeout);
        $this->assertSame(600, $pull->uniqueFor());
        $this->assertSame(3, $pull->tries);
    }

    public function testItIsUniquePerAccountModulesAndKind(): void
    {
        $this->assertSame('acct-test:customer,saleList:incremental', new Pull([Module::Customer, Module::SaleList])->uniqueId());
        $this->assertSame('acct-test:customer:full', new Pull([Module::Customer], full: true)->uniqueId());
    }
}
