<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Support\Carbon;
use Hypervel\Support\Sleep;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Testing\Cin7Fake;

/**
 * A document module's pull: which list rows it reads a document for, how many, and what a
 * failure or a vanished list row leaves.
 *
 * @see docs/sync.md
 */
class DocumentPullTest extends SyncTestCase
{
    public function testADocumentIsReadForEachListRowWithoutOneOldestFirst(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-02T09:00:00Z'), $this->sale('s-2', '2026-10-01T09:00:00Z')]),
            Cin7Fake::record(['ID' => 's-2', 'Status' => 'ORDERED']),
            Cin7Fake::record(['ID' => 's-1', 'Status' => 'COMPLETED']),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $result = $this->synchroniser()->pull(Module::Sale);

        $this->assertSame([2, 2], [$result->read, $result->written]);
        $this->assertFalse($result->failed());
        $this->assertSame(['saleList', 'sale', 'sale'], $this->endpoints($mock));
        $this->assertSame(['s-2', 's-1'], array_column(array_slice($this->queries($mock), 1), 'ID'));

        $documents = $this->stored(Module::Sale);
        $this->assertSame(['ID' => 's-1', 'Status' => 'COMPLETED'], $documents['s-1']->payload);
        // A document carries its list row's modified time.
        $this->assertSame('2026-10-02 09:00:00.000', $documents['s-1']->cin7_modified_at?->format('Y-m-d H:i:s.v'));
        Sleep::assertSequence([Sleep::usleep(1_000_000)]);
    }

    public function testListRowsWithoutAModifiedTimeAreReadFirstInListOrder(): void
    {
        $undated = static fn (string $id): array => ['SaleID' => $id, 'OrderNumber' => 'SO-' . $id, 'Updated' => null];

        $mock = Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-02T09:00:00Z'), $undated('s-2'), $this->sale('s-3', '2026-10-01T09:00:00Z'), $undated('s-4')]),
            Cin7Fake::record(['ID' => 's-2']),
            Cin7Fake::record(['ID' => 's-4']),
            Cin7Fake::record(['ID' => 's-3']),
            Cin7Fake::record(['ID' => 's-1']),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $this->synchroniser()->pull(Module::Sale);

        // The same on every database: PostgreSQL sorts NULLs last, SQLite and MySQL first.
        $this->assertSame(['s-2', 's-4', 's-3', 's-1'], array_column(array_slice($this->queries($mock), 1), 'ID'));
    }

    public function testOnlyAListRowThatChangedMakesItsDocumentPendingAgain(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1'), $this->sale('s-2')]),
            Cin7Fake::record(['ID' => 's-1']),
            Cin7Fake::record(['ID' => 's-2']),
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-03T12:30:00Z'), $this->sale('s-2')]),
            Cin7Fake::record(['ID' => 's-1', 'Status' => 'VOIDED']),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $this->synchroniser()->pull(Module::Sale);
        $this->travel(1)->hours();
        $this->synchroniser()->pull(Module::SaleList);
        $result = $this->synchroniser()->pull(Module::Sale);

        $this->assertSame(1, $result->read);
        $this->assertSame('s-1', $this->queries($mock)[4]['ID']);
        $this->assertSame('VOIDED', $this->stored(Module::Sale)['s-1']->payload['Status']);
    }

    public function testADocumentStoredWithoutAModifiedTimeIsReadOnceItsListRowGetsOne(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::list('SaleList', [['SaleID' => 's-1', 'OrderNumber' => 'SO-s-1', 'Updated' => null]]),
            Cin7Fake::record(['ID' => 's-1']),
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-03T12:30:00Z')]),
            Cin7Fake::record(['ID' => 's-1', 'Status' => 'VOIDED']),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $this->synchroniser()->pull(Module::Sale);
        $this->travel(1)->hours();
        $this->synchroniser()->pull(Module::SaleList);
        $second = $this->synchroniser()->pull(Module::Sale);
        $third = $this->synchroniser()->pull(Module::Sale);

        $this->assertSame([1, 0], [$second->read, $third->read]);
        $this->assertSame('VOIDED', $this->stored(Module::Sale)['s-1']->payload['Status']);
        $mock->assertSentCount(4);
    }

    public function testAtMostTheConfiguredNumberAreReadInARun(): void
    {
        $this->app->get('config')->set('cin7.sync.documents', 1);

        Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-02T09:00:00Z'), $this->sale('s-2', '2026-10-01T09:00:00Z')]),
            Cin7Fake::record(['ID' => 's-2']),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $result = $this->synchroniser()->pull(Module::Sale);

        $this->assertSame(1, $result->read);
        $this->assertSame(['s-2'], $this->stored(Module::Sale)->keys()->all());
    }

    public function testZeroDocumentsReadsNone(): void
    {
        $this->app->get('config')->set('cin7.sync.documents', 0);

        $mock = Saloon::fake([Cin7Fake::list('SaleList', [$this->sale('s-1')])]);

        $this->synchroniser()->pull(Module::SaleList);
        $result = $this->synchroniser()->pull(Module::Sale);

        $this->assertSame(0, $result->read);
        $mock->assertSentCount(1);
    }

    public function testAFailingDocumentStaysPendingAndTheRestAreStillRead(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-01T09:00:00Z'), $this->sale('s-2', '2026-10-02T09:00:00Z')]),
            Cin7Fake::error('Sale not found'),
            Cin7Fake::record(['ID' => 's-2']),
            Cin7Fake::record(['ID' => 's-1']),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $failed = $this->synchroniser()->pull(Module::Sale);
        $retried = $this->synchroniser()->pull(Module::Sale);

        // A document error does not fail the module: the other document was written.
        $this->assertFalse($failed->failed());
        $this->assertSame([2, 1, 1], [$failed->read, $failed->written, $failed->failedDocuments]);
        $this->assertStringContainsString('Sale not found', (string) $failed->documentError?->getMessage());
        $this->assertSame([1, 1, 0], [$retried->read, $retried->written, $retried->failedDocuments]);
        $this->assertSame('s-1', $this->queries($mock)[3]['ID']);
        $this->assertSame(['s-1', 's-2'], $this->stored(Module::Sale)->keys()->all());
    }

    public function testAFailedReadIsCountedOnTheListRow(): void
    {
        Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-01T09:00:00Z'), $this->sale('s-2', '2026-10-02T09:00:00Z')]),
            Cin7Fake::error(str_repeat('x', 300)),
            Cin7Fake::record(['ID' => 's-2']),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $updated = $this->stored(Module::SaleList)['s-1']->updated_at;
        $this->synchroniser()->pull(Module::Sale);

        $lists = $this->stored(Module::SaleList);
        $this->assertSame([1, 0], [$lists['s-1']->failures, $lists['s-2']->failures]);
        $this->assertSame('2026-10-03 12:00:00', $lists['s-1']->failed_at?->format('Y-m-d H:i:s'));
        $this->assertStringContainsString('xxxxxxxx', (string) $lists['s-1']->last_error);
        $this->assertLessThanOrEqual(255, strlen((string) $lists['s-1']->last_error));
        $this->assertNull($lists['s-2']->failed_at);
        // The counter is not a change of the record.
        $this->assertEquals($updated, $lists['s-1']->updated_at);
    }

    public function testADocumentIsNotReadOnceItHasFailedTheConfiguredNumberOfTimes(): void
    {
        $this->app->get('config')->set('cin7.sync.max_failures', 2);

        $mock = Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-01T09:00:00Z'), $this->sale('s-2', '2026-10-02T09:00:00Z')]),
            Cin7Fake::error('Sale not found'),
            Cin7Fake::record(['ID' => 's-2']),
            Cin7Fake::list('SaleList', [$this->sale('s-3', '2026-10-02T10:00:00Z')]),
            Cin7Fake::error('Sale not found'),
            Cin7Fake::record(['ID' => 's-3']),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $first = $this->synchroniser()->pull(Module::Sale);
        $this->travel(1)->hours();
        $this->synchroniser()->pull(Module::SaleList);
        $second = $this->synchroniser()->pull(Module::Sale);
        $third = $this->synchroniser()->pull(Module::Sale);

        $this->assertSame([2, 2, 0], [$first->read, $second->read, $third->read]);
        $this->assertFalse($second->failed());
        // s-1 has failed twice and is parked, so a third run has nothing to send.
        $this->assertSame(['saleList', 'sale', 'sale', 'saleList', 'sale', 'sale'], $this->endpoints($mock));
        $this->assertSame(2, $this->stored(Module::SaleList)['s-1']->failures);
    }

    public function testAChangedListRowMakesAParkedDocumentPendingAgain(): void
    {
        $this->app->get('config')->set('cin7.sync.max_failures', 1);

        $mock = Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1'), $this->sale('s-2')]),
            Cin7Fake::error('Sale not found'),
            Cin7Fake::record(['ID' => 's-2']),
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-03T12:30:00Z')]),
            Cin7Fake::record(['ID' => 's-1', 'Status' => 'VOIDED']),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $this->synchroniser()->pull(Module::Sale);
        $this->assertSame(0, $this->synchroniser()->pull(Module::Sale)->read);
        $this->travel(1)->hours();
        $this->synchroniser()->pull(Module::SaleList);
        $retried = $this->synchroniser()->pull(Module::Sale);

        $this->assertSame([1, 1], [$retried->read, $retried->written]);
        $list = $this->stored(Module::SaleList)['s-1'];
        $this->assertSame(0, $list->failures);
        $this->assertNull($list->failed_at);
        $this->assertNull($list->last_error);
        $this->assertSame('s-1', $this->queries($mock)[4]['ID']);
    }

    public function testAnUnchangedListRowKeepsItsFailures(): void
    {
        Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1'), $this->sale('s-2')]),
            Cin7Fake::error('Sale not found'),
            Cin7Fake::record(['ID' => 's-2']),
            Cin7Fake::list('SaleList', [$this->sale('s-1'), $this->sale('s-2')]),
            Cin7Fake::list('SaleList', [$this->sale('s-1'), $this->sale('s-2')]),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $this->synchroniser()->pull(Module::Sale);
        $this->travel(1)->hours();
        $this->synchroniser()->pull(Module::SaleList);
        $this->synchroniser()->pull(Module::SaleList, full: true);

        $this->assertSame(1, $this->stored(Module::SaleList)['s-1']->failures);
    }

    public function testARunWhereEveryDocumentFailsFailsTheModuleAndBlamesNoRow(): void
    {
        Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-01T09:00:00Z'), $this->sale('s-2', '2026-10-02T09:00:00Z')]),
            Cin7Fake::error('Something went wrong'),
            Cin7Fake::error('Something went wrong'),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $result = $this->synchroniser()->pull(Module::Sale);

        $this->assertTrue($result->failed());
        $this->assertSame([2, 0, 0], [$result->read, $result->written, $result->failedDocuments]);
        $this->assertSame([0, 0], $this->stored(Module::SaleList)->pluck('failures')->all());
    }

    public function testEveryPurchaseIsReadFromAdvancedPurchase(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::list('PurchaseList', [
                $this->purchase('p-1', '2026-10-01T09:00:00Z'),
                $this->purchase('p-2', '2026-10-02T09:00:00Z', 'Advanced Purchase'),
            ]),
            Cin7Fake::record(['ID' => 'p-1', 'Type' => 'Simple Purchase']),
            Cin7Fake::record(['ID' => 'p-2', 'Type' => 'Advanced Purchase']),
        ]);

        $this->synchroniser()->pull(Module::PurchaseList);
        $result = $this->synchroniser()->pull(Module::AdvancedPurchase);

        $this->assertSame(2, $result->written);
        $this->assertSame(['purchaseList', 'advanced-purchase', 'advanced-purchase'], $this->endpoints($mock));
        $this->assertSame(['p-1', 'p-2'], $this->stored(Module::AdvancedPurchase)->keys()->all());
    }

    public function testAFullPullDeletesTheDocumentsWhoseListRowIsGone(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1'), $this->sale('s-2')]),
            Cin7Fake::record(['ID' => 's-1']),
            Cin7Fake::record(['ID' => 's-2']),
            Cin7Fake::list('SaleList', [$this->sale('s-1')]),
            Cin7Fake::list('SaleList'),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $this->synchroniser()->pull(Module::Sale);
        $this->travel(1)->hours();
        $this->synchroniser()->pull(Module::SaleList, full: true);
        $result = $this->synchroniser()->pull(Module::Sale, full: true);

        $this->assertSame([0, 1], [$result->read, $result->deleted]);
        $this->assertSame(['s-1'], $this->stored(Module::Sale)->keys()->all());
        $mock->assertSentCount(5);
    }

    public function testAFullPullKeepsARecordUpdatedWhileTheBoundedWalkRan(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1'), $this->sale('s-2')]),
            Cin7Fake::record(['ID' => 's-1']),
            Cin7Fake::record(['ID' => 's-2']),
            // The bounded walk leaves s-2 out, as UpdatedUntil would; the follow-up walk returns it.
            Cin7Fake::list('SaleList', [$this->sale('s-1')]),
            Cin7Fake::list('SaleList', [$this->sale('s-2', '2026-10-03T12:00:01Z')]),
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $this->synchroniser()->pull(Module::Sale);
        $this->travel(1)->hours();
        $list = $this->synchroniser()->pull(Module::SaleList, full: true);
        $document = $this->synchroniser()->pull(Module::Sale, full: true);

        $this->assertSame([2, 0], [$list->read, $list->deleted]);
        $this->assertSame(0, $document->deleted);
        $this->assertSame(['s-1', 's-2'], $this->stored(Module::SaleList)->keys()->all());
        $this->assertSame(['s-1', 's-2'], $this->stored(Module::Sale)->keys()->all());

        [, , , $bounded, $followUp] = $this->queries($mock);
        $this->assertArrayHasKey('UpdatedUntil', $bounded);
        $this->assertArrayNotHasKey('UpdatedSince', $bounded);
        $this->assertArrayNotHasKey('UpdatedUntil', $followUp);
        $this->assertSame(Carbon::now()->startOfSecond()->format('Y-m-d\TH:i:s.v'), $followUp['UpdatedSince']);
    }
}
