<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Support\Facades\Artisan;
use Hypervel\Support\Facades\Cache;
use Hypervel\Support\Facades\DB;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\Payload;
use Ipsocode\Cin7\Testing\Cin7Fake;

/**
 * `cin7:sync`, the pull in the foreground. Off, it refuses: see ServiceProviderTest.
 *
 * @see src/docs/sync.md
 */
class SyncCommandTest extends SyncTestCase
{
    public function testItPullsTheModulesItIsGiven(): void
    {
        $mock = Saloon::fake([
            Cin7Fake::list('SupplierList', [['ID' => 's-1', 'LastModifiedOn' => '2026-10-01T10:00:00Z']]),
            Cin7Fake::list('CustomerList', [$this->customer('c-1'), $this->customer('c-2')]),
        ]);

        $this->artisan('cin7:sync supplier customer')
            ->expectsTable(
                ['Module', 'Pull', 'Read', 'Written', 'Unchanged', 'Deleted', 'Result'],
                [['supplier', 'full', 1, 1, 0, 0, 'ok'], ['customer', 'full', 2, 2, 0, 0, 'ok']],
            )
            ->assertExitCode(0);

        $this->assertSame(['supplier', 'customer'], $this->endpoints($mock));
    }

    public function testWithoutModulesItPullsTheConfiguredOnes(): void
    {
        $this->app->get('config')->set('cin7.sync.modules', ['customer']);

        $mock = Saloon::fake([Cin7Fake::list('CustomerList', [$this->customer('c-1')])]);

        $this->artisan('cin7:sync')->assertExitCode(0);

        $this->assertSame(['customer'], $this->endpoints($mock));
    }

    public function testAStarPullsEveryModuleInDependencyOrder(): void
    {
        $this->app->get('config')->set('cin7.sync.modules', ['customer']);

        $mock = Saloon::fake(array_map(Cin7Fake::list(...), [
            'AccountsList', 'BankAccountsList', 'LocationList', 'TaxRuleList', 'PaymentTermList', 'CategoryList',
            'BrandList', 'UnitList', 'CarrierList', 'AttributeSetList', 'FixedAssetTypeList',
            'CustomerList', 'SupplierList', 'Products', 'SaleList', 'PurchaseList',
        ]));

        $this->artisan('cin7:sync', ['module' => ['*']])->assertExitCode(0);

        // The documents have no list rows to read, so they send nothing.
        $this->assertSame([
            'ref/account', 'ref/account/bank', 'ref/location', 'ref/tax', 'ref/paymentterm', 'ref/category',
            'ref/brand', 'ref/unit', 'ref/carrier', 'ref/attributeset', 'ref/fixedassettype',
            'customer', 'supplier', 'product', 'saleList', 'purchaseList',
        ], $this->endpoints($mock));
    }

    public function testFullMakesEachPullAFullOne(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList', [$this->customer('c-1')]), Cin7Fake::list('CustomerList', [$this->customer('c-1')])]);

        $this->artisan('cin7:sync customer')->assertExitCode(0);

        $this->artisan('cin7:sync customer --full')
            ->expectsTable(
                ['Module', 'Pull', 'Read', 'Written', 'Unchanged', 'Deleted', 'Result'],
                [['customer', 'full', 1, 0, 1, 0, 'ok']],
            )
            ->assertExitCode(0);
    }

    public function testAFailedModuleFailsTheCommand(): void
    {
        Saloon::fake([Cin7Fake::error('Something went wrong'), Cin7Fake::list('SupplierList')]);

        $this->artisan('cin7:sync customer supplier')
            ->expectsOutputToContain('failed: ')
            ->assertExitCode(1);
    }

    public function testASkippedModuleSaysSo(): void
    {
        Saloon::fake([]);
        Cache::lock('cin7:sync:acct-test:customer', 60)->get();

        $this->artisan('cin7:sync customer')
            ->expectsOutputToContain('skipped: another pull holds its lock')
            ->assertExitCode(0);
    }

    public function testAnUnknownModuleIsRefused(): void
    {
        $this->artisan('cin7:sync invoices')
            ->expectsOutputToContain('cin7.sync names the module [invoices]')
            ->assertExitCode(1);
    }

    public function testStatusShowsWhatTheTableHoldsAndPullsNothing(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList', [$this->customer('c-1'), $this->customer('c-2', '2026-10-02T08:00:00.5Z')])]);
        $this->artisan('cin7:sync customer')->assertExitCode(0);

        // fake() keeps the existing client, so start the second phase from a clean recorder.
        Saloon::clearFake();
        $mock = Saloon::fake([]);

        $this->artisan('cin7:sync customer supplier --status')
            ->expectsTable(
                ['Module', 'Rows', 'Parked documents', 'Last synced', 'Newest change in Cin7'],
                [['customer', 2, '-', '2026-10-03 12:00:00', '2026-10-02 08:00:00.500'], ['supplier', 0, '-', 'never', '-']],
            )
            ->assertExitCode(0);

        $mock->assertNothingSent();
    }

    public function testStatusGroupsEveryModuleAndCountsOnlyTheAccountsParkedDocuments(): void
    {
        $this->app->get('config')->set('cin7.sync.max_failures', 2);

        $this->store([
            ['acct-test', 'customer', 'c-1', '2026-10-01 10:00:00.123', '2026-10-02 08:00:00', 0],
            ['acct-test', 'customer', 'c-2', '2026-10-02 09:30:00.500', '2026-10-03 11:00:00', 0],
            ['acct-test', 'saleList', 's-1', '2026-10-01 09:00:00.000', '2026-10-03 11:00:00', 2],
            ['acct-test', 'saleList', 's-2', '2026-10-01 09:00:00.000', '2026-10-03 11:00:00', 1],
            ['acct-test', 'sale', 's-2', '2026-10-01 09:00:00.000', '2026-10-03 11:30:00', 0],
            ['acct-test', 'purchaseList', 'p-1', null, '2026-10-03 11:00:00', 3],
            ['acct-other', 'customer', 'c-9', '2026-10-03 09:00:00.000', '2026-10-03 11:59:00', 0],
            ['acct-other', 'saleList', 's-9', '2026-10-01 09:00:00.000', '2026-10-03 11:00:00', 5],
        ]);

        $this->artisan('cin7:sync customer supplier sale advanced-purchase purchaseList --status')
            ->expectsTable(
                ['Module', 'Rows', 'Parked documents', 'Last synced', 'Newest change in Cin7'],
                [
                    ['customer', 2, '-', '2026-10-03 11:00:00', '2026-10-02 09:30:00.500'],
                    ['supplier', 0, '-', 'never', '-'],
                    ['sale', 1, 1, '2026-10-03 11:30:00', '2026-10-01 09:00:00.000'],
                    ['advanced-purchase', 0, 1, 'never', '-'],
                    ['purchaseList', 1, '-', '2026-10-03 11:00:00', '-'],
                ],
            )
            ->assertExitCode(0);
    }

    public function testStatusIssuesTwoQueriesWhateverTheModuleCount(): void
    {
        $queries = 0;
        DB::listen(function () use (&$queries): void {
            ++$queries;
        });

        $this->artisan('cin7:sync customer --status')->assertExitCode(0);
        $this->assertSame(2, $queries);

        $queries = 0;
        $this->artisan('cin7:sync', ['module' => ['*'], '--status' => true])->assertExitCode(0);
        $this->assertSame(2, $queries);
    }

    public function testADocumentThatFailedIsShownAndTheCommandStillSucceeds(): void
    {
        Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-01T09:00:00Z'), $this->sale('s-2', '2026-10-02T09:00:00Z')]),
            Cin7Fake::error('Sale not found'),
            Cin7Fake::record(['ID' => 's-2']),
        ]);

        $this->artisan('cin7:sync saleList sale')
            ->expectsOutputToContain('ok, 1 documents failed: ')
            ->assertExitCode(0);
    }

    public function testStatusCountsTheParkedDocumentsAndRetryFailedReleasesThem(): void
    {
        $this->app->get('config')->set('cin7.sync.max_failures', 1);

        $mock = Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-01T09:00:00Z'), $this->sale('s-2', '2026-10-02T09:00:00Z')]),
            Cin7Fake::error('Sale not found'),
            Cin7Fake::record(['ID' => 's-2']),
            Cin7Fake::record(['ID' => 's-1']),
        ]);
        $this->artisan('cin7:sync saleList sale')->assertExitCode(0);

        $this->artisan('cin7:sync sale --status')
            ->expectsTable(
                ['Module', 'Rows', 'Parked documents', 'Last synced', 'Newest change in Cin7'],
                [['sale', 1, 1, '2026-10-03 12:00:00', '2026-10-02 09:00:00.000']],
            )
            ->assertExitCode(0);

        $this->artisan('cin7:sync sale --retry-failed')
            ->expectsOutputToContain('sale: 1 documents released.')
            ->assertExitCode(0);

        $this->assertSame(['s-1', 's-2'], $this->stored(Module::Sale)->keys()->all());
        $this->assertSame([0, 0], $this->stored(Module::SaleList)->pluck('failures')->all());
        $mock->assertSentCount(4);
    }

    public function testRetryFailedLeavesAListModuleAlone(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList', [$this->customer('c-1')])]);

        $this->artisan('cin7:sync customer --retry-failed')->doesntExpectOutputToContain('released')->assertExitCode(0);
    }

    public function testStatusIgnoresRetryFailed(): void
    {
        $this->app->get('config')->set('cin7.sync.max_failures', 1);

        Saloon::fake([
            Cin7Fake::list('SaleList', [$this->sale('s-1', '2026-10-01T09:00:00Z'), $this->sale('s-2', '2026-10-02T09:00:00Z')]),
            Cin7Fake::error('Sale not found'),
            Cin7Fake::record(['ID' => 's-2']),
        ]);
        $this->artisan('cin7:sync saleList sale')->assertExitCode(0);

        $parked = $this->stored(Module::SaleList)->map(static fn ($row): array => [$row->failures, (string) $row->failed_at, $row->last_error])->all();

        $this->artisan('cin7:sync sale --status --retry-failed')
            ->doesntExpectOutputToContain('released')
            ->assertExitCode(0);

        $this->assertSame($parked, $this->stored(Module::SaleList)->map(static fn ($row): array => [$row->failures, (string) $row->failed_at, $row->last_error])->all());
        $this->assertSame(1, $this->stored(Module::SaleList)->max('failures'));
    }

    public function testNothingItPrintsCarriesTheCredentials(): void
    {
        Saloon::fake([Cin7Fake::list('CustomerList', [$this->customer('c-1')]), Cin7Fake::credentialsRejected()]);
        $this->app->get('config')->set('cin7.retry.times', 1);

        foreach (['cin7:sync customer', 'cin7:sync customer --status', 'cin7:sync supplier'] as $command) {
            Artisan::call($command);
            $output = Artisan::output();

            $this->assertStringNotContainsString('acct-test', $output, $command);
            $this->assertStringNotContainsString('key-test', $output, $command);
        }

        $this->assertSame(['c-1'], $this->stored(Module::Customer)->keys()->all());
    }

    /**
     * Store rows straight into the table: account, module, identifier, modified, synced, failures.
     *
     * @param list<array{string, string, string, null|string, string, int}> $rows
     */
    private function store(array $rows): void
    {
        Payload::query()->toBase()->insert(array_map(static fn (array $row): array => [
            'account_id' => $row[0],
            'module' => $row[1],
            'cin7_id' => $row[2],
            'payload' => '{}',
            'cin7_modified_at' => $row[3],
            'synced_at' => $row[4],
            'failures' => $row[5],
        ], $rows));
    }
}
