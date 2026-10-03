<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use Hypervel\Saloon\Facades\Saloon;
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

        $this->assertTrue($failed->failed());
        $this->assertSame([2, 1], [$failed->read, $failed->written]);
        $this->assertSame([1, 1], [$retried->read, $retried->written]);
        $this->assertSame('s-1', $this->queries($mock)[3]['ID']);
        $this->assertSame(['s-1', 's-2'], $this->stored(Module::Sale)->keys()->all());
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
        ]);

        $this->synchroniser()->pull(Module::SaleList);
        $this->synchroniser()->pull(Module::Sale);
        $this->travel(1)->hours();
        $this->synchroniser()->pull(Module::SaleList, full: true);
        $result = $this->synchroniser()->pull(Module::Sale, full: true);

        $this->assertSame([0, 1], [$result->read, $result->deleted]);
        $this->assertSame(['s-1'], $this->stored(Module::Sale)->keys()->all());
        $mock->assertSentCount(4);
    }
}
