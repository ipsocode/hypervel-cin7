<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Support\Facades\Cache;
use Hypervel\Support\Sleep;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\Payload;
use Ipsocode\Cin7\Testing\Cin7Fake;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * A list module's pull: the window it asks for, what it writes, and what a failure leaves.
 *
 * @see docs/sync.md
 */
class ListPullTest extends SyncTestCase
{
    public function testAFirstPullIsFullAndStoresEachRecordUnderItsModulesIdentifier(): void
    {
        $mock = Saloon::fake([Cin7Fake::list('CustomerList', [
            $this->customer('c-1'),
            $this->customer('c-2', name: 'Globex'),
        ])]);

        $result = $this->synchroniser()->pull(Module::Customer);

        $this->assertTrue($result->full);
        $this->assertSame([2, 2, 0, 0], [$result->read, $result->written, $result->unchanged, $result->deleted]);
        $this->assertFalse($result->failed());
        $this->assertFalse($result->skipped);
        $this->assertSame([['IncludeDeprecated' => 'true', 'page' => 1, 'limit' => 500]], $this->queries($mock));

        $rows = $this->stored(Module::Customer);
        $this->assertSame(['c-1', 'c-2'], $rows->keys()->all());
        $this->assertSame('acct-test', $rows['c-1']->account_id);
        $this->assertSame($this->customer('c-2', name: 'Globex'), $rows['c-2']->payload);
        $this->assertSame('2026-10-01 10:00:00.123', $rows['c-1']->cin7_modified_at?->format('Y-m-d H:i:s.v'));
        $this->assertSame('2026-10-03 12:00:00', $rows['c-1']->synced_at->toDateTimeString());
        $this->assertSame('2026-10-03 12:00:00', $rows['c-1']->updated_at?->toDateTimeString());
    }

    /**
     * @param array<string, mixed> $record
     * @param list<string> $filters
     */
    #[DataProvider('listModules')]
    public function testTheNextPullAsksForWhatChangedInTheLookback(Module $module, string $listKey, array $record, array $filters): void
    {
        $mock = Saloon::fake([Cin7Fake::list($listKey, [$record]), Cin7Fake::list($listKey, [$record])]);

        $this->synchroniser()->pull($module);
        $this->travel(1)->hours();
        $result = $this->synchroniser()->pull($module);

        [$first, $second] = $this->queries($mock);
        $this->assertArrayNotHasKey($filters[0], $first);
        $this->assertSame('2026-10-02T13:00:00.000', $second[$filters[0]]);
        $this->assertSame(
            $filters[1] === 'UpdatedUntil' ? '2026-10-03T13:00:00.000' : 'true',
            $second[$filters[1]],
        );
        $this->assertFalse($result->full);
        $this->assertSame(1, $result->unchanged);
        $this->assertSame('r-1', $this->stored($module)->keys()->first());
    }

    /**
     * @return iterable<string, array{Module, string, array<string, mixed>, list<string>}>
     */
    public static function listModules(): iterable
    {
        $record = ['ID' => 'r-1', 'LastModifiedOn' => '2026-10-01T10:00:00Z'];

        yield 'customer' => [Module::Customer, 'CustomerList', $record, ['ModifiedSince', 'IncludeDeprecated']];
        yield 'supplier' => [Module::Supplier, 'SupplierList', $record, ['ModifiedSince', 'IncludeDeprecated']];
        yield 'product' => [Module::Product, 'Products', $record, ['ModifiedSince', 'IncludeDeprecated']];
        yield 'saleList' => [Module::SaleList, 'SaleList', ['SaleID' => 'r-1', 'Updated' => '2026-10-01T10:00:00Z'], ['UpdatedSince', 'UpdatedUntil']];
        yield 'purchaseList' => [Module::PurchaseList, 'PurchaseList', ['ID' => 'r-1', 'LastUpdatedDate' => '2026-10-01T10:00:00Z'], ['UpdatedSince', 'UpdatedUntil']];
    }

    public function testALongerGapReachesBackToTheLastSync(): void
    {
        $mock = Saloon::fake([Cin7Fake::list('CustomerList', [$this->customer('c-1')]), Cin7Fake::list('CustomerList')]);

        $this->synchroniser()->pull(Module::Customer);
        $this->travel(3)->days();
        $this->synchroniser()->pull(Module::Customer);

        $this->assertSame('2026-10-03T12:00:00.000', $this->queries($mock)[1]['ModifiedSince']);
    }

    public function testARecordReadAgainUnchangedOnlyMovesItsSyncedAt(): void
    {
        Saloon::fake([
            Cin7Fake::list('CustomerList', [$this->customer('c-1'), $this->customer('c-2')]),
            Cin7Fake::list('CustomerList', [
                $this->customer('c-1', name: 'Renamed without a new modified time'),
                $this->customer('c-2', '2026-10-03T12:30:00Z', 'Globex'),
            ]),
        ]);

        $this->synchroniser()->pull(Module::Customer);
        $this->travel(1)->hours();
        $result = $this->synchroniser()->pull(Module::Customer);

        $this->assertSame([2, 1, 1], [$result->read, $result->written, $result->unchanged]);

        $rows = $this->stored(Module::Customer);
        $this->assertSame('ACME', $rows['c-1']->payload['Name']);
        $this->assertSame('2026-10-03 12:00:00', $rows['c-1']->updated_at?->toDateTimeString());
        $this->assertSame('2026-10-03 13:00:00', $rows['c-1']->synced_at->toDateTimeString());
        $this->assertSame('Globex', $rows['c-2']->payload['Name']);
        $this->assertSame('2026-10-03 13:00:00', $rows['c-2']->updated_at?->toDateTimeString());
        $this->assertSame('2026-10-03 12:30:00.000', $rows['c-2']->cin7_modified_at?->format('Y-m-d H:i:s.v'));
    }

    public function testAFullPullRefreshesAnUnchangedPayloadWithoutMovingUpdatedAt(): void
    {
        Saloon::fake([
            Cin7Fake::list('CustomerList', [$this->customer('c-1')]),
            Cin7Fake::list('CustomerList', [$this->customer('c-1', name: 'Renamed')]),
        ]);

        $this->synchroniser()->pull(Module::Customer);
        $this->travel(1)->hours();
        $result = $this->synchroniser()->pull(Module::Customer, full: true);

        $this->assertTrue($result->full);
        $this->assertSame(1, $result->unchanged);

        $row = $this->stored(Module::Customer)['c-1'];
        $this->assertSame('Renamed', $row->payload['Name']);
        $this->assertSame('2026-10-03 12:00:00', $row->updated_at?->toDateTimeString());
        $this->assertSame('2026-10-03 13:00:00', $row->synced_at->toDateTimeString());
    }

    public function testAFullPullDeletesTheRowsItDidNotSee(): void
    {
        Saloon::fake([
            Cin7Fake::list('CustomerList', [$this->customer('c-1'), $this->customer('c-2')]),
            Cin7Fake::list('CustomerList', [$this->customer('c-1')]),
        ]);

        $this->synchroniser()->pull(Module::Customer);
        $this->travel(1)->hours();
        $result = $this->synchroniser()->pull(Module::Customer, full: true);

        $this->assertSame(1, $result->deleted);
        $this->assertSame(['c-1'], $this->stored(Module::Customer)->keys()->all());
    }

    public function testAFullPullThatReadsNothingDeletesNothing(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList', [$this->customer('c-1')]), Cin7Fake::list('CustomerList')]);

        $this->synchroniser()->pull(Module::Customer);
        $this->travel(1)->hours();
        $result = $this->synchroniser()->pull(Module::Customer, full: true);

        $this->assertSame([0, 0], [$result->read, $result->deleted]);
        $this->assertSame(['c-1'], $this->stored(Module::Customer)->keys()->all());
    }

    public function testAPullThatFailsOnALaterPageKeepsWhatItWroteAndDeletesNothing(): void
    {
        $this->app->get('config')->set('cin7.sync.limit', 2);

        Saloon::fake([
            Cin7Fake::list('CustomerList', [$this->customer('c-1'), $this->customer('c-2')], total: 3),
            Cin7Fake::list('CustomerList', [$this->customer('c-3')], page: 2, total: 3),
            Cin7Fake::list('CustomerList', [$this->customer('c-1', '2026-10-03T12:30:00Z', 'Renamed'), $this->customer('c-2')], total: 3),
            Cin7Fake::error('Something went wrong'),
        ]);

        $this->synchroniser()->pull(Module::Customer);
        $this->travel(1)->hours();
        $result = $this->synchroniser()->pull(Module::Customer, full: true);

        $this->assertTrue($result->failed());
        $this->assertStringContainsString('400', (string) $result->error?->getMessage());
        $this->assertSame([2, 1, 1, 0], [$result->read, $result->written, $result->unchanged, $result->deleted]);

        $rows = $this->stored(Module::Customer);
        $this->assertSame(['c-1', 'c-2', 'c-3'], $rows->keys()->all());
        $this->assertSame('Renamed', $rows['c-1']->payload['Name']);
    }

    public function testAFailedFirstFillLeavesTheModuleEmpty(): void
    {
        $this->app->get('config')->set('cin7.sync.limit', 1);

        Saloon::fake([
            Cin7Fake::list('CustomerList', [$this->customer('c-1')], total: 2),
            Cin7Fake::error('Something went wrong'),
        ]);

        $result = $this->synchroniser()->pull(Module::Customer);

        $this->assertTrue($result->full);
        $this->assertTrue($result->failed());
        $this->assertCount(0, $this->stored(Module::Customer));
    }

    public function testRecordsWithoutAnIdentifierAreSkipped(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList', [
            $this->customer('c-1'),
            ['Name' => 'No identifier'],
            ['ID' => '', 'Name' => 'Empty identifier'],
            'not a record',
        ])]);

        $result = $this->synchroniser()->pull(Module::Customer);

        $this->assertSame([4, 1], [$result->read, $result->written]);
        $this->assertSame(['c-1'], $this->stored(Module::Customer)->keys()->all());
    }

    public function testARecordWithoutAReadableModifiedTimeIsWrittenEveryTime(): void
    {
        Saloon::fake([
            Cin7Fake::list('CustomerList', [$this->customer('c-1', null), $this->customer('c-2', 'not a date')]),
            Cin7Fake::list('CustomerList', [$this->customer('c-1', null), $this->customer('c-2', 'not a date')]),
        ]);

        $this->synchroniser()->pull(Module::Customer);
        $result = $this->synchroniser()->pull(Module::Customer);

        $this->assertSame([2, 0], [$result->written, $result->unchanged]);
        $this->assertNull($this->stored(Module::Customer)['c-2']->cin7_modified_at);
    }

    public function testItPausesBetweenItsCallsOnly(): void
    {
        $this->app->get('config')->set('cin7.sync.limit', 1);

        Saloon::fake([
            Cin7Fake::list('CustomerList', [$this->customer('c-1')], total: 3),
            Cin7Fake::list('CustomerList', [$this->customer('c-2')], page: 2, total: 3),
            Cin7Fake::list('CustomerList', [$this->customer('c-3')], page: 3, total: 3),
        ]);

        $result = $this->synchroniser()->pull(Module::Customer);

        $this->assertSame(3, $result->written);
        Sleep::assertSequence([Sleep::usleep(1_000_000), Sleep::usleep(1_000_000)]);
    }

    public function testAPauseOfZeroDoesNotWait(): void
    {
        $this->app->get('config')->set('cin7.sync.limit', 1);
        $this->app->get('config')->set('cin7.sync.pause_ms', 0);

        Saloon::fake([
            Cin7Fake::list('CustomerList', [$this->customer('c-1')], total: 2),
            Cin7Fake::list('CustomerList', [$this->customer('c-2')], page: 2, total: 2),
        ]);

        $this->synchroniser()->pull(Module::Customer);

        Sleep::assertNeverSlept();
    }

    public function testAHeldLockSkipsTheModule(): void
    {
        $mock = Saloon::fake([]);
        Cache::lock('cin7:sync:acct-test:customer', 60)->get();

        $result = $this->synchroniser()->pull(Module::Customer);

        $this->assertTrue($result->skipped);
        $this->assertFalse($result->failed());
        $mock->assertNothingSent();
    }

    public function testThePullReleasesItsLock(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList')]);

        $this->synchroniser()->pull(Module::Customer);

        $this->assertTrue(Cache::lock('cin7:sync:acct-test:customer', 60)->get());
    }

    public function testEachModuleIsPulledInOrderAndAFailureDoesNotStopTheNext(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::error('Something went wrong'),
            Cin7Fake::list('SupplierList', [['ID' => 's-1', 'LastModifiedOn' => '2026-10-01T10:00:00Z']]),
        ]);

        $results = $this->synchroniser()->pullMany([Module::Customer, Module::Supplier]);

        $this->assertSame(['customer', 'supplier'], $this->endpoints($mock));
        $this->assertTrue($results[0]->failed());
        $this->assertFalse($results[1]->failed());
        $this->assertSame(['s-1'], $this->stored(Module::Supplier)->keys()->all());
    }

    public function testRowsAreKeptPerAccount(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList', [$this->customer('c-1')])]);

        $this->synchroniser()->pull(Module::Customer);

        $this->assertCount(1, Payload::forModule(Module::Customer, 'acct-test')->get());
        $this->assertCount(0, Payload::forModule('customer', 'another-account')->get());
    }

    public function testAReferenceBookIsReadWholeEveryTime(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::list('LocationList', [['ID' => 'l-1', 'Name' => 'Main']]),
            Cin7Fake::list('LocationList', [['ID' => 'l-1', 'Name' => 'Main']]),
        ]);

        $this->synchroniser()->pull(Module::Location);
        $this->travel(1)->hours();
        $result = $this->synchroniser()->pull(Module::Location);

        $this->assertTrue($result->full);
        $this->assertSame([['page' => 1, 'limit' => 500], ['page' => 1, 'limit' => 500]], $this->queries($mock));
    }

    /**
     * A reference book's records carry no modified time, so a pull compares the payload.
     */
    public function testAReferenceBookRecordChangesWhenItsPayloadDoes(): void
    {
        Saloon::fake([
            Cin7Fake::list('AccountsList', [['Code' => '200', 'Name' => 'Sales'], ['Code' => '610', 'Name' => 'Receivables'], ['Code' => '800', 'Name' => 'Payables']]),
            Cin7Fake::list('AccountsList', [['Code' => '200', 'Name' => 'Sales'], ['Code' => '610', 'Name' => 'Trade receivables']]),
        ]);

        $this->synchroniser()->pull(Module::Account);
        $this->travel(1)->hours();
        $result = $this->synchroniser()->pull(Module::Account);

        $this->assertSame([2, 1, 1, 1], [$result->read, $result->written, $result->unchanged, $result->deleted]);

        $rows = $this->stored(Module::Account);
        $this->assertSame(['200', '610'], array_map(strval(...), $rows->keys()->all()));
        $this->assertNull($rows['200']->cin7_modified_at);
        $this->assertSame('2026-10-03 12:00:00', $rows['200']->updated_at?->toDateTimeString());
        $this->assertSame('2026-10-03 13:00:00', $rows['200']->synced_at->toDateTimeString());
        $this->assertSame('Trade receivables', $rows['610']->payload['Name']);
        $this->assertSame('2026-10-03 13:00:00', $rows['610']->updated_at?->toDateTimeString());
    }
}
