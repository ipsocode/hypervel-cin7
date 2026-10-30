<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Sync;

use Closure;
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
 * @see src/docs/sync.md
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
        return $this->definition()['source'];
    }

    /**
     * The key a record's identifier sits under, in the payload this module stores.
     */
    public function idKey(): string
    {
        return $this->definition()['id'];
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
     * Tell whether an incremental walk of the list sends an upper bound, `UpdatedUntil`, so a
     * record updated during the walk does not join its end: a list module with a modified time
     * that cannot be asked for one record by identifier.
     */
    public function bounded(): bool
    {
        return $this->modifiedKey() !== null && $this->source() === null && ! $this->confirms();
    }

    /**
     * The request for the document of one record of a bounded list, which says whether Cin7 still
     * has the record, because the list has no filter for its identifier.
     *
     * @return Cin7Request<covariant Data>
     *
     * @throws LogicException for a module that is not a bounded list
     */
    public function existenceRequest(string $id): Cin7Request
    {
        return match ($this) {
            self::SaleList => new GetSale($id),
            self::PurchaseList => new GetAdvancedPurchase($id),
            default => throw new LogicException("[{$this->value}] is not a bounded list."),
        };
    }

    /**
     * Tell whether a full pull can ask Cin7 for one record of the list by identifier, before it
     * deletes a row it did not see. Not so for a bounded list, which is asked by document, a
     * reference book, which is read whole, and a document module.
     */
    public function confirms(): bool
    {
        return $this->definition()['find'] !== null;
    }

    /**
     * The request for one record of a list module by identifier, sent before a full pull deletes a
     * row it did not see.
     *
     * @return ListRequest<covariant Data&WithResponse>
     *
     * @throws LogicException for a module that does not confirm
     */
    public function findRequest(string $id): ListRequest
    {
        $find = $this->definition()['find'];

        return $find === null
            ? throw new LogicException("[{$this->value}] cannot be asked for one record by identifier.")
            : $find($id);
    }

    /**
     * The key of Cin7's modified time in a list module's records. A document takes its list
     * row's.
     */
    public function modifiedKey(): ?string
    {
        return $this->definition()['modified'];
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
    public function listRequest(?DateTimeInterface $since, ?DateTimeInterface $until): ListRequest
    {
        $list = $this->definition()['list'];

        return $list === null
            ? throw new LogicException("[{$this->value}] is a document module: it reads the rows of its list.")
            : $list($since, $until);
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
        $document = $this->definition()['document'];

        return $document === null
            ? throw new LogicException("[{$this->value}] is a list module: it is paged, not read one record at a time.")
            : $document($id);
    }

    /**
     * The data class one stored payload maps to.
     *
     * @return class-string<Data>
     */
    public function dataClass(): string
    {
        return $this->definition()['data'];
    }

    /**
     * Everything the sync knows about a module, in one place. The match has no default, so a case
     * without a definition throws rather than taking one.
     *
     * - `id`: the key of a record's identifier.
     * - `modified`: the key of the modified time; null for a reference book and a document.
     * - `source`: the list module a document module reads; null for a list module.
     * - `data`: the data class a stored payload maps to.
     * - `list`: builds the list request; null for a document module. A closure rather than a
     *   request, because the since and until arguments and the deprecated records differ by module.
     * - `document`: builds the request for one document; null for a list module.
     * - `find`: builds the request for one record by identifier; null for a list that has no filter
     *   for it, which existenceRequest() asks by document instead.
     *
     * @return array{
     *     id: string,
     *     modified: ?string,
     *     source: ?self,
     *     data: class-string<Data>,
     *     list: null|(Closure(?DateTimeInterface, ?DateTimeInterface): ListRequest<covariant Data&WithResponse>),
     *     document: null|(Closure(string): Cin7Request<covariant Data>),
     *     find: null|(Closure(string): ListRequest<covariant Data&WithResponse>),
     * }
     */
    private function definition(): array
    {
        return match ($this) {
            self::Account => $this->reference('Code', AccountData::class, static fn () => new GetAccount),
            self::BankAccount => $this->reference('AccountID', BankAccountData::class, static fn () => new GetAccountBank),
            self::Location => $this->reference('ID', LocationData::class, static fn () => new GetLocation),
            self::Tax => $this->reference('ID', TaxData::class, static fn () => new GetTax),
            self::PaymentTerm => $this->reference('ID', PaymentTermData::class, static fn () => new GetPaymentTerm),
            self::Category => $this->reference('ID', ProductCategoryData::class, static fn () => new GetCategory),
            self::Brand => $this->reference('ID', BrandData::class, static fn () => new GetBrand),
            self::Unit => $this->reference('ID', UnitOfMeasureData::class, static fn () => new GetUnit),
            self::Carrier => $this->reference('CarrierID', CarrierData::class, static fn () => new GetCarrier),
            self::AttributeSet => $this->reference('ID', AttributeSetData::class, static fn () => new GetAttributeSet),
            self::FixedAssetType => $this->reference('FixedAssetTypeID', FixedAssetTypeData::class, static fn () => new GetFixedAssetType),
            self::Customer => [
                'id' => 'ID',
                'modified' => 'LastModifiedOn',
                'source' => null,
                'data' => CustomerData::class,
                'list' => static fn (?DateTimeInterface $since) => new GetCustomer(modifiedSince: $since, includeDeprecated: true),
                'document' => null,
                'find' => static fn (string $id): GetCustomer => new GetCustomer(id: $id, includeDeprecated: true),
            ],
            self::Supplier => [
                'id' => 'ID',
                'modified' => 'LastModifiedOn',
                'source' => null,
                'data' => SupplierData::class,
                'list' => static fn (?DateTimeInterface $since) => new GetSupplier(modifiedSince: $since, includeDeprecated: true),
                'document' => null,
                'find' => static fn (string $id): GetSupplier => new GetSupplier(id: $id, includeDeprecated: true),
            ],
            self::Product => [
                'id' => 'ID',
                'modified' => 'LastModifiedOn',
                'source' => null,
                'data' => ProductData::class,
                'list' => static fn (?DateTimeInterface $since) => new GetProduct(modifiedSince: $since, includeDeprecated: true),
                'document' => null,
                'find' => static fn (string $id): GetProduct => new GetProduct(id: $id, includeDeprecated: true),
            ],
            // An upper bound keeps a record updated during the walk off its end.
            self::SaleList => [
                'id' => 'SaleID',
                'modified' => 'Updated',
                'source' => null,
                'data' => SaleListData::class,
                'list' => static fn (?DateTimeInterface $since, ?DateTimeInterface $until) => new GetSaleList(updatedSince: $since, updatedUntil: $until),
                'document' => null,
                'find' => null,
            ],
            self::PurchaseList => [
                'id' => 'ID',
                'modified' => 'LastUpdatedDate',
                'source' => null,
                'data' => PurchaseListData::class,
                'list' => static fn (?DateTimeInterface $since, ?DateTimeInterface $until) => new GetPurchaseList(updatedSince: $since, updatedUntil: $until),
                'document' => null,
                'find' => null,
            ],
            self::Sale => [
                'id' => 'ID',
                'modified' => null,
                'source' => self::SaleList,
                'data' => SaleData::class,
                'list' => null,
                'document' => static fn (string $id) => new GetSale($id),
                'find' => null,
            ],
            self::AdvancedPurchase => [
                'id' => 'ID',
                'modified' => null,
                'source' => self::PurchaseList,
                'data' => AdvancedPurchaseData::class,
                'list' => null,
                'document' => static fn (string $id) => new GetAdvancedPurchase($id),
                'find' => null,
            ],
        };
    }

    /**
     * The definition of a reference book: no modified time, no source, read whole on every pull.
     *
     * @param class-string<Data> $data
     * @param Closure(): ListRequest<covariant Data&WithResponse> $list
     *
     * @return array{id: string, modified: null, source: null, data: class-string<Data>, list: Closure(): ListRequest<covariant Data&WithResponse>, document: null, find: null}
     */
    private function reference(string $id, string $data, Closure $list): array
    {
        return ['id' => $id, 'modified' => null, 'source' => null, 'data' => $data, 'list' => $list, 'document' => null, 'find' => null];
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
