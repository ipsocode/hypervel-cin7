<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Sync;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
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
use Ipsocode\Cin7\Requests\Cin7Request;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\ListRequest;
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
use LogicException;
use Throwable;

/**
 * An endpoint the sync copies, named by its request path. A list module is paged: an incremental
 * one with a since filter, a reference book whole on every pull. A document module reads one
 * record per call, for the rows of its list module that changed.
 *
 * The cases are in the order `'*'` pulls them, the records others point to before the records
 * that point to them: the accounts, the locations and the other reference books, then customers
 * and suppliers, then products, then the sale and purchase lists, each before the documents read
 * from it. A new case keeps that order.
 *
 * @see docs/sync.md
 */
enum Module: string
{
    case Account = 'ref/account';
    case BankAccount = 'ref/account/bank';
    case Location = 'ref/location';
    case Tax = 'ref/tax';
    case PaymentTerm = 'ref/paymentterm';
    case Category = 'ref/category';
    case Brand = 'ref/brand';
    case Unit = 'ref/unit';
    case Carrier = 'ref/carrier';
    case AttributeSet = 'ref/attributeset';
    case FixedAssetType = 'ref/fixedassettype';
    case Customer = 'customer';
    case Supplier = 'supplier';
    case Product = 'product';
    case SaleList = 'saleList';
    case PurchaseList = 'purchaseList';
    case Sale = 'sale';

    // The reference marks `purchase` deprecated, for simple purchases only, and has
    // `advanced-purchase` serve simple, advanced and service purchases alike.
    case AdvancedPurchase = 'advanced-purchase';

    /**
     * The list module whose rows a document module reads; null for a list module.
     */
    public function source(): ?self
    {
        return match ($this) {
            self::Sale => self::SaleList,
            self::AdvancedPurchase => self::PurchaseList,
            default => null,
        };
    }

    /**
     * The key a record's identifier sits under, in the payload this module stores.
     */
    public function idKey(): string
    {
        return match ($this) {
            self::Account => 'Code',
            self::BankAccount => 'AccountID',
            self::Carrier => 'CarrierID',
            self::FixedAssetType => 'FixedAssetTypeID',
            self::SaleList => 'SaleID',
            default => 'ID',
        };
    }

    /**
     * Whether a pull reads only what changed: a list with a since filter, or the documents of
     * the list rows that changed. A reference book has no since filter and its records no
     * modified time, so every pull reads it whole and compares the payloads; the schedule leaves
     * it to the full pull.
     */
    public function incremental(): bool
    {
        return $this->modifiedKey() !== null || $this->source() !== null;
    }

    /**
     * The key of Cin7's modified time in a list module's records. A document takes its list
     * row's.
     */
    public function modifiedKey(): ?string
    {
        return match ($this) {
            self::Customer, self::Supplier, self::Product => 'LastModifiedOn',
            self::SaleList => 'Updated',
            self::PurchaseList => 'LastUpdatedDate',
            default => null,
        };
    }

    /**
     * The list request a pull pages: everything changed since `$since`, or every record when it
     * is null or the module is a reference book. Deprecated records are asked for too, so
     * deprecating one arrives as a change.
     *
     * @return ListRequest<covariant Data&WithResponse>
     *
     * @throws LogicException for a document module, which has no list of its own
     */
    public function listRequest(?DateTimeInterface $since, DateTimeInterface $until): ListRequest
    {
        return match ($this) {
            self::Account => new GetAccount,
            self::BankAccount => new GetAccountBank,
            self::Location => new GetLocation,
            self::Tax => new GetTax,
            self::PaymentTerm => new GetPaymentTerm,
            self::Category => new GetCategory,
            self::Brand => new GetBrand,
            self::Unit => new GetUnit,
            self::Carrier => new GetCarrier,
            self::AttributeSet => new GetAttributeSet,
            self::FixedAssetType => new GetFixedAssetType,
            self::Customer => new GetCustomer(modifiedSince: $since, includeDeprecated: true),
            self::Supplier => new GetSupplier(modifiedSince: $since, includeDeprecated: true),
            self::Product => new GetProduct(modifiedSince: $since, includeDeprecated: true),
            // An upper bound keeps the pages still while they are walked.
            self::SaleList => new GetSaleList(updatedSince: $since, updatedUntil: $until),
            self::PurchaseList => new GetPurchaseList(updatedSince: $since, updatedUntil: $until),
            default => throw new LogicException("[{$this->value}] is a document module: it reads the rows of its list."),
        };
    }

    /**
     * The request for one document.
     *
     * @return Cin7Request<covariant Data>
     *
     * @throws LogicException for a list module, which is paged
     */
    public function documentRequest(string $id): Cin7Request
    {
        return match ($this) {
            self::Sale => new GetSale($id),
            self::AdvancedPurchase => new GetAdvancedPurchase($id),
            default => throw new LogicException("[{$this->value}] is a list module: it is paged, not read one record at a time."),
        };
    }

    /**
     * The data class one stored payload maps to.
     *
     * @return class-string<Data>
     */
    public function dataClass(): string
    {
        return match ($this) {
            self::Account => AccountData::class,
            self::BankAccount => BankAccountData::class,
            self::Location => LocationData::class,
            self::Tax => TaxData::class,
            self::PaymentTerm => PaymentTermData::class,
            self::Category => ProductCategoryData::class,
            self::Brand => BrandData::class,
            self::Unit => UnitOfMeasureData::class,
            self::Carrier => CarrierData::class,
            self::AttributeSet => AttributeSetData::class,
            self::FixedAssetType => FixedAssetTypeData::class,
            self::Customer => CustomerData::class,
            self::Supplier => SupplierData::class,
            self::Product => ProductData::class,
            self::SaleList => SaleListData::class,
            self::PurchaseList => PurchaseListData::class,
            self::Sale => SaleData::class,
            self::AdvancedPurchase => AdvancedPurchaseData::class,
        };
    }

    /**
     * A record's modified time as stored, UTC to the millisecond, or null when it has none.
     *
     * @param array<array-key, mixed> $record
     */
    public function modifiedAt(array $record): ?string
    {
        $key = $this->modifiedKey();

        return self::timestamp($key === null ? null : ($record[$key] ?? null));
    }

    /**
     * A time from Cin7 or the database, as stored: UTC to the millisecond. Cin7 sends UTC, with or
     * without the `Z`; anything unreadable is null.
     */
    public static function timestamp(mixed $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return new DateTimeImmutable($value, new DateTimeZone('UTC'))
                ->setTimezone(new DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s.v');
        } catch (Throwable) {
            return null;
        }
    }
}
