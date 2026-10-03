<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use Hypervel\Saloon\Facades\Saloon;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Data\SaleList\SaleListData;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Sync\Payload;
use Ipsocode\Cin7\Testing\Cin7Fake;
use Workbench\App\Support\Cin7Payloads;

/**
 * Reading the table back.
 *
 * @see docs/sync.md
 */
class PayloadTest extends SyncTestCase
{
    public function testARowReadsBackAsItsModulesDataObject(): void
    {
        $customer = Cin7Payloads::customerExample()['CustomerList'][0];
        $sale = Cin7Payloads::saleList()['SaleList'][0];

        Saloon::fake([Cin7Fake::list('CustomerList', [$customer]), Cin7Fake::list('SaleList', [$sale])]);

        $this->synchroniser()->pull(Module::Customer);
        $this->synchroniser()->pull(Module::SaleList);

        $data = Payload::forModule(Module::Customer)->sole()->data();
        $this->assertInstanceOf(CustomerData::class, $data);
        $this->assertSame($customer['Name'], $data->Name);

        $this->assertInstanceOf(SaleListData::class, Payload::forModule('saleList')->sole()->data());
    }

    public function testTheTableIsOnTheConfiguredConnection(): void
    {
        $this->assertNull(new Payload()->getConnectionName());

        $this->app->get('config')->set('cin7.sync.connection', 'reporting');

        $this->assertSame('reporting', new Payload()->getConnectionName());
        $this->assertSame('cin7_sync_payloads', new Payload()->getTable());
    }
}
