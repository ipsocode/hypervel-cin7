<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use InvalidArgumentException;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\SyncConfig;
use Ipsocode\Cin7\Tests\TestCase;

/**
 * `cin7.sync.*` is cast and clamped as it is read, never trusted.
 *
 * @see docs/configuration.md
 */
class SyncConfigTest extends TestCase
{
    public function testThePackagedDefaults(): void
    {
        $this->assertFalse(SyncConfig::enabled());
        $this->assertSame('0 * * * *', SyncConfig::cron());
        $this->assertSame(Module::cases(), SyncConfig::modules());
        $this->assertSame([], SyncConfig::exceptions());
        $this->assertSame('0 2 * * 0', SyncConfig::full());
        $this->assertSame(1440, SyncConfig::lookback());
        $this->assertSame(500, SyncConfig::limit());
        $this->assertSame(1000, SyncConfig::pauseMs());
        $this->assertSame(250, SyncConfig::documents());
        $this->assertNull(SyncConfig::queue());
        $this->assertSame(3600, SyncConfig::timeout());
        $this->assertNull(SyncConfig::connection());
    }

    /**
     * What records point to comes first: the accounts, the locations, the other reference books,
     * customers and suppliers, products, then each document list before its documents.
     */
    public function testEveryModuleIsPulledInDependencyOrder(): void
    {
        $this->assertSame(
            ['ref/account', 'ref/account/bank', 'ref/location', 'ref/tax', 'ref/paymentterm', 'ref/category', 'ref/brand', 'ref/unit', 'ref/carrier', 'ref/attributeset', 'ref/fixedassettype', 'customer', 'supplier', 'product', 'saleList', 'purchaseList', 'sale', 'advanced-purchase'],
            array_column(SyncConfig::modules(), 'value'),
        );
    }

    public function testAListOfModulesKeepsItsOrder(): void
    {
        $this->config(['modules' => ['saleList', 'customer']]);

        $this->assertSame([Module::SaleList, Module::Customer], SyncConfig::modules());
    }

    public function testAStarAnywhereInTheListIsEveryModule(): void
    {
        $this->config(['modules' => ['customer', '*']]);
        $this->assertSame(Module::cases(), SyncConfig::modules());

        $this->config(['modules' => null]);
        $this->assertSame(Module::cases(), SyncConfig::modules());
    }

    public function testAnUnknownModuleFailsLoudly(): void
    {
        $this->config(['modules' => ['customer', 'invoices']]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cin7.sync names the module [invoices], which is not one of: ref/account, ref/account/bank, ref/location, ref/tax, ref/paymentterm, ref/category, ref/brand, ref/unit, ref/carrier, ref/attributeset, ref/fixedassettype, customer, supplier, product, saleList, purchaseList, sale, advanced-purchase.');

        SyncConfig::modules();
    }

    public function testAnExceptionWithoutATimeIsLeftOut(): void
    {
        $this->config(['exceptions' => ['saleList' => ' */5 * * * * ', 'customer' => null, 'product' => '']]);

        $this->assertSame([[Module::SaleList, '*/5 * * * *']], SyncConfig::exceptions());
    }

    public function testABlankOrNullTimeSchedulesNothing(): void
    {
        $this->config(['cron' => '  ', 'full' => null]);

        $this->assertNull(SyncConfig::cron());
        $this->assertNull(SyncConfig::full());
    }

    public function testTheNumbersAreCastAndClamped(): void
    {
        $this->config(['lookback' => '-5', 'limit' => '5000', 'pause_ms' => -1, 'documents' => -3, 'timeout' => 0]);

        $this->assertSame([0, 1000, 0, 0, 1], [SyncConfig::lookback(), SyncConfig::limit(), SyncConfig::pauseMs(), SyncConfig::documents(), SyncConfig::timeout()]);

        $this->config(['limit' => 0]);
        $this->assertSame(1, SyncConfig::limit());
    }

    public function testAnAbsentNumberFallsBackToItsDefault(): void
    {
        $this->app->get('config')->set('cin7.sync', ['enabled' => true]);

        $this->assertSame([1440, 500, 1000, 250, 3600], [SyncConfig::lookback(), SyncConfig::limit(), SyncConfig::pauseMs(), SyncConfig::documents(), SyncConfig::timeout()]);
    }

    public function testAnEmptyQueueOrConnectionIsTheDefaultOne(): void
    {
        $this->config(['queue' => '', 'connection' => 'cin7']);

        $this->assertNull(SyncConfig::queue());
        $this->assertSame('cin7', SyncConfig::connection());
    }

    public function testTheCommonPullLeavesTheReferenceBooksToTheFullPull(): void
    {
        $this->assertSame(
            ['customer', 'supplier', 'product', 'saleList', 'purchaseList', 'sale', 'advanced-purchase'],
            array_column(SyncConfig::scheduled(), 'value'),
        );

        $this->config(['modules' => ['ref/tax', 'saleList']]);
        $this->assertSame([Module::SaleList], SyncConfig::scheduled());
    }

    public function testAReferenceBookCannotBeAnException(): void
    {
        $this->config(['exceptions' => ['ref/location' => '*/5 * * * *']]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cin7.sync.exceptions names [ref/location], which Cin7 can only send whole.');

        SyncConfig::exceptions();
    }

    /**
     * @param array<string, mixed> $values
     */
    private function config(array $values): void
    {
        foreach ($values as $key => $value) {
            $this->app->get('config')->set('cin7.sync.' . $key, $value);
        }
    }
}
