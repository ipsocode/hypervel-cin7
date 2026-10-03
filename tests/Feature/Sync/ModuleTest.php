<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use Hypervel\Support\Carbon;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchaseData;
use Ipsocode\Cin7\Data\Customer\CustomerData;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Data\PurchaseList\PurchaseListData;
use Ipsocode\Cin7\Data\Ref\Account\AccountData;
use Ipsocode\Cin7\Data\Ref\Account\Bank\BankAccountData;
use Ipsocode\Cin7\Data\Ref\AttributeSet\AttributeSetData;
use Ipsocode\Cin7\Data\Ref\Brand\BrandData;
use Ipsocode\Cin7\Data\Ref\Carrier\CarrierData;
use Ipsocode\Cin7\Data\Ref\Category\ProductCategoryData;
use Ipsocode\Cin7\Data\Ref\FixedAssetType\FixedAssetTypeData;
use Ipsocode\Cin7\Data\Ref\Location\LocationData;
use Ipsocode\Cin7\Data\Ref\PaymentTerm\PaymentTermData;
use Ipsocode\Cin7\Data\Ref\Tax\TaxData;
use Ipsocode\Cin7\Data\Ref\Unit\UnitOfMeasureData;
use Ipsocode\Cin7\Data\Sale\SaleData;
use Ipsocode\Cin7\Data\SaleList\SaleListData;
use Ipsocode\Cin7\Data\Supplier\SupplierData;
use Ipsocode\Cin7\Requests\AdvancedPurchase\GetAdvancedPurchase;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Product\GetProduct;
use Ipsocode\Cin7\Requests\PurchaseList\GetPurchaseList;
use Ipsocode\Cin7\Requests\Ref\Account\Bank\GetAccountBank;
use Ipsocode\Cin7\Requests\Ref\Account\GetAccount;
use Ipsocode\Cin7\Requests\Ref\AttributeSet\GetAttributeSet;
use Ipsocode\Cin7\Requests\Ref\Brand\GetBrand;
use Ipsocode\Cin7\Requests\Ref\Carrier\GetCarrier;
use Ipsocode\Cin7\Requests\Ref\Category\GetCategory;
use Ipsocode\Cin7\Requests\Ref\FixedAssetType\GetFixedAssetType;
use Ipsocode\Cin7\Requests\Ref\Location\GetLocation;
use Ipsocode\Cin7\Requests\Ref\PaymentTerm\GetPaymentTerm;
use Ipsocode\Cin7\Requests\Ref\Tax\GetTax;
use Ipsocode\Cin7\Requests\Ref\Unit\GetUnit;
use Ipsocode\Cin7\Requests\Sale\GetSale;
use Ipsocode\Cin7\Requests\SaleList\GetSaleList;
use Ipsocode\Cin7\Requests\Supplier\GetSupplier;
use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Tests\TestCase;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * What each module reads, and how a Cin7 time is stored.
 *
 * @see docs/sync.md
 */
class ModuleTest extends TestCase
{
    /**
     * @param class-string $request
     * @param class-string $data
     */
    #[DataProvider('modules')]
    public function testWhatEachModuleReads(Module $module, string $idKey, ?string $modifiedKey, string $request, string $data, ?Module $source): void
    {
        $this->assertSame($idKey, $module->idKey());
        $this->assertSame($modifiedKey, $module->modifiedKey());
        $this->assertSame($modifiedKey !== null || $source !== null, $module->incremental());
        $this->assertSame($data, $module->dataClass());
        $this->assertSame($source, $module->source());

        $this->assertInstanceOf($request, $source === null ? $module->listRequest(null, Carbon::now()) : $module->documentRequest('id'));

        // The other kind of request is a mistake in the package, not a request to send.
        $this->expectException(LogicException::class);

        $source === null ? $module->documentRequest('id') : $module->listRequest(null, Carbon::now());
    }

    /**
     * @return iterable<string, array{Module, string, ?string, class-string, class-string, ?Module}>
     */
    public static function modules(): iterable
    {
        yield 'ref/account' => [Module::Account, 'Code', null, GetAccount::class, AccountData::class, null];
        yield 'ref/account/bank' => [Module::BankAccount, 'AccountID', null, GetAccountBank::class, BankAccountData::class, null];
        yield 'ref/location' => [Module::Location, 'ID', null, GetLocation::class, LocationData::class, null];
        yield 'ref/tax' => [Module::Tax, 'ID', null, GetTax::class, TaxData::class, null];
        yield 'ref/paymentterm' => [Module::PaymentTerm, 'ID', null, GetPaymentTerm::class, PaymentTermData::class, null];
        yield 'ref/category' => [Module::Category, 'ID', null, GetCategory::class, ProductCategoryData::class, null];
        yield 'ref/brand' => [Module::Brand, 'ID', null, GetBrand::class, BrandData::class, null];
        yield 'ref/unit' => [Module::Unit, 'ID', null, GetUnit::class, UnitOfMeasureData::class, null];
        yield 'ref/carrier' => [Module::Carrier, 'CarrierID', null, GetCarrier::class, CarrierData::class, null];
        yield 'ref/attributeset' => [Module::AttributeSet, 'ID', null, GetAttributeSet::class, AttributeSetData::class, null];
        yield 'ref/fixedassettype' => [Module::FixedAssetType, 'FixedAssetTypeID', null, GetFixedAssetType::class, FixedAssetTypeData::class, null];
        yield 'customer' => [Module::Customer, 'ID', 'LastModifiedOn', GetCustomer::class, CustomerData::class, null];
        yield 'supplier' => [Module::Supplier, 'ID', 'LastModifiedOn', GetSupplier::class, SupplierData::class, null];
        yield 'product' => [Module::Product, 'ID', 'LastModifiedOn', GetProduct::class, ProductData::class, null];
        yield 'saleList' => [Module::SaleList, 'SaleID', 'Updated', GetSaleList::class, SaleListData::class, null];
        yield 'purchaseList' => [Module::PurchaseList, 'ID', 'LastUpdatedDate', GetPurchaseList::class, PurchaseListData::class, null];
        yield 'sale' => [Module::Sale, 'ID', null, GetSale::class, SaleData::class, Module::SaleList];
        yield 'advanced-purchase' => [Module::AdvancedPurchase, 'ID', null, GetAdvancedPurchase::class, AdvancedPurchaseData::class, Module::PurchaseList];
    }

    public function testEveryModuleIsInTheTable(): void
    {
        $this->assertSame(
            array_column(Module::cases(), 'value'),
            array_keys(iterator_to_array(self::modules())),
        );
    }

    #[DataProvider('times')]
    public function testATimeIsStoredInUtcToTheMillisecond(mixed $value, ?string $stored): void
    {
        $this->assertSame($stored, Module::timestamp($value));
    }

    /**
     * @return iterable<string, array{mixed, ?string}>
     */
    public static function times(): iterable
    {
        yield 'UTC with Z' => ['2017-09-29T03:00:48.043Z', '2017-09-29 03:00:48.043'];
        yield 'no zone, read as UTC' => ['2017-09-29T03:00:48.043', '2017-09-29 03:00:48.043'];
        yield 'more digits than milliseconds' => ['2017-11-22T10:10:49.7512345Z', '2017-11-22 10:10:49.751'];
        yield 'an offset' => ['2017-09-29T05:00:48+02:00', '2017-09-29 03:00:48.000'];
        yield 'as the database returns it' => ['2017-09-29 03:00:48', '2017-09-29 03:00:48.000'];
        yield 'blank' => ['  ', null];
        yield 'null' => [null, null];
        yield 'not a string' => [20170929, null];
        yield 'not a date' => ['not a date', null];
    }

    public function testADocumentModuleHasNoModifiedTimeOfItsOwn(): void
    {
        $this->assertNull(Module::Sale->modifiedAt(['Updated' => '2017-09-29T03:00:48Z']));
        $this->assertSame('2017-09-29 03:00:48.000', Module::SaleList->modifiedAt(['Updated' => '2017-09-29T03:00:48Z']));
    }
}
