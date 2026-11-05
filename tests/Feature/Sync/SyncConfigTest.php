<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use InvalidArgumentException;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\SyncConfig;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * `cin7.sync.*` is cast and range-checked as it is read, never trusted.
 *
 * @see src/docs/configuration.md
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
    }

    public function testANullModuleListFailsNamingTheKey(): void
    {
        $this->config(['modules' => null]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cin7.sync.modules');

        SyncConfig::modules();
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

    #[DataProvider('outOfRangeNumbers')]
    public function testANumberOutsideItsRangeFailsNamingTheKeyTheValueAndTheRange(string $key, int $value, string $method, string $range): void
    {
        $this->config([$key => $value]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Configuration value for key [cin7.sync.%s] must be %s, %d given.', $key, $range, $value));

        SyncConfig::$method();
    }

    /**
     * @return array<string, array{string, int, string, string}>
     */
    public static function outOfRangeNumbers(): array
    {
        return [
            'lookback' => ['lookback', -5, 'lookback', 'at least 0'],
            'limit above' => ['limit', 5000, 'limit', '1 to 1000'],
            'limit below' => ['limit', 0, 'limit', '1 to 1000'],
            'pause_ms' => ['pause_ms', -1, 'pauseMs', 'at least 0'],
            'documents' => ['documents', -3, 'documents', 'at least 0'],
            'timeout' => ['timeout', 0, 'timeout', 'at least 1'],
            'max_failures below' => ['max_failures', 0, 'maxFailures', '1 to 32767'],
            'max_failures above' => ['max_failures', SyncConfig::MAX_FAILURES_MAX + 1, 'maxFailures', '1 to 32767'],
        ];
    }

    public function testTheNumbersAtTheEdgesOfTheirRangesAreKept(): void
    {
        $this->config(['lookback' => 0, 'limit' => 1000, 'pause_ms' => 0, 'documents' => 0, 'timeout' => 1, 'max_failures' => SyncConfig::MAX_FAILURES_MAX]);

        $this->assertSame([0, 1000, 0, 0, 1, 32767], [SyncConfig::lookback(), SyncConfig::limit(), SyncConfig::pauseMs(), SyncConfig::documents(), SyncConfig::timeout(), SyncConfig::maxFailures()]);

        $this->config(['limit' => 1, 'max_failures' => 1]);
        $this->assertSame([1, 1], [SyncConfig::limit(), SyncConfig::maxFailures()]);
    }

    public function testANullNumberFailsNamingTheKey(): void
    {
        $this->config(['lookback' => null]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('cin7.sync.lookback');

        SyncConfig::lookback();
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
