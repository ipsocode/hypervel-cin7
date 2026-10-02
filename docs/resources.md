# Resources

Every Cin7 call goes through a resource, reached from `Cin7Connector` by an
accessor that spells the V2 path: `$cin7->customer()`. A resource is a thin,
stateless wrapper around the request classes described in
[requests](requests.md); it builds the request and sends it, or hands it to
`paginate()` for a list. The accessor chain, not a generic client call, is the
public surface of this package.

```php
use Ipsocode\Cin7\Cin7Connector;

public function __construct(private readonly Cin7Connector $cin7) {}

// GET customer?page=1&limit=100
$all = $this->cin7->customer()->get()->json();

// POST customer, body {"Name":"ACME"}
$new = $this->cin7->customer()->post(['Name' => 'ACME'])->json();

// PUT customer, body {"ID":"…","Name":"ACME Ltd"}
$this->cin7->customer()->put(['ID' => $guid, 'Name' => 'ACME Ltd']);

// Every customer, across all pages
foreach ($this->cin7->customer()->paginate()->items() as $customer) {
    // $customer is one entry of CustomerList
}
```

## Conventions

- **Folders mirror the V2 path.** Below `ExternalApi/v2/`, every segment of a
  path is a StudlyCase folder under `src/Requests/`: `sale/invoice` becomes
  `src/Requests/Sale/Invoice/`. Under `src/Resources/`, the last segment names
  the class instead, so `customer` is `src/Resources/CustomerResource.php` and
  `sale/invoice` is `src/Resources/Sale/InvoiceResource.php`.
- **The accessor chain spells the path.** `$cin7->customer()`,
  `$cin7->sale()->invoice()`.
- **One path is named after its model.** `moneyOperation` serves the Money Task, which also
  names the reference's group of money endpoints, so its folders, classes and accessor say
  `MoneyTask`: `src/Requests/MoneyTask/GetMoneyTask.php`, `MoneyTaskResource` and
  `$cin7->moneyTask()`. The requests still send `moneyOperation`.
- **Methods are HTTP verbs.** `get()`, `post()`, `put()`, `delete()`, plus
  `paginate()` on list endpoints.
- **Query parameters are typed named arguments.** A keyed `get()` or `delete()` takes the
  identifier first, then each optional parameter the reference documents:
  `get(string $id, ?bool $combineAdditionalCharges = null, …)`. A list's `get()` takes `page`,
  `limit` and the list's filters the same way, and its `paginate()` the same arguments without
  `page`. See [query parameters](requests.md#query-parameters) for every request's arguments.
- **A write's identifier is the caller's job.** `post()` and `put()` take the
  body verbatim; the caller merges in the identifier a PUT needs (see
  [PUT identifiers](#put-identifiers)).
- **Resources are stateless.** `BaseResource` holds only the connector, and
  every accessor on `Cin7Connector` returns a fresh instance, so the resource
  never becomes state shared across coroutines. See
  [requests](requests.md) for the request classes a resource builds.

## The accessor tree

| Accessor | Resource | Methods |
|---|---|---|
| `$cin7->customer()` | `CustomerResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|CustomerPostData $body)`, `put(array\|CustomerPutData $body)` |
| `$cin7->supplier()` | `SupplierResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|SupplierPostData $body)`, `put(array\|SupplierPutData $body)` |
| `$cin7->me()` | `MeResource` | `get()`; `addresses()`, `contacts()` |
| `$cin7->me()->addresses()` | `Me\AddressesResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|MeAddressPostData $body)`, `put(array\|MeAddressPutData $body)`, `delete(string $id)` |
| `$cin7->me()->contacts()` | `Me\ContactsResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|MeContactPostData $body)`, `put(array\|MeContactPutData $body)`, `delete(string $id)` |
| `$cin7->product()` | `ProductResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|ProductPostData $body)`, `put(array\|ProductPutData $body)`; `attachments()`, `markupPrices()` |
| `$cin7->customPrices()` | `CustomPricesResource` | `post(array\|CustomPricesData $body)`, `put(array\|CustomPricesData $body)`, `delete(string $productId, string $customerId)` |
| `$cin7->productSuppliers()` | `ProductSuppliersResource` | `get(string $productId)`, `post(array\|ProductSuppliersData $body)`, `put(array\|ProductSuppliersData $body)`, `delete(string $productId, string $supplierId)` |
| `$cin7->reference()` | `ReferenceResource` | `deals()`, `discount()`, `shipZones()`, `shipZonesEnabled()` |
| `$cin7->reference()->shipZones()` | `Reference\ShipZonesResource` | `get($page, $limit, $id, $search)`, `paginate($limit, $id, $search): Cin7Paginator`, `post(array\|ShippingZonePostData $body)`, `put(array\|ShippingZonePutData $body)`, `delete(string $shipZoneId)` |
| `$cin7->reference()->discount()` | `Reference\DiscountResource` | `get($page, $limit, $id, $search)`, `paginate($limit, $id, $search): Cin7Paginator`, `post(array\|ProductDiscountRulesPostData $body)`, `put(array\|ProductDiscountRulePutData $body)` |
| `$cin7->reference()->deals()` | `Reference\DealsResource` | `get($page, $limit, $id, $search)`, `paginate($limit, $id, $search): Cin7Paginator`, `post(array\|ProductDealPostData $body)`, `put(array\|ProductDealPutData $body)` |
| `$cin7->reference()->shipZonesEnabled()` | `Reference\ShipZonesEnabledResource` | `get()`, `put(array\|ShipZonesEnabledData $body)` |
| `$cin7->bankTransfer()` | `BankTransferResource` | `get(string $taskId)`, `post(array\|BankTransferPostData $body)`, `put(array\|BankTransferPutData $body)`, `delete(string $id, ?bool $void = null)` |
| `$cin7->journal()` | `JournalResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|JournalPostData $body)`, `put(array\|JournalPutData $body)`, `delete(string $id, ?bool $void = null)` |
| `$cin7->stockAdjustmentList()` | `StockAdjustmentListResource` | `get($page, $limit, ?CompletionStatus $status)`, `paginate($limit, ?CompletionStatus $status): Cin7Paginator` |
| `$cin7->stockAdjustment()` | `StockAdjustmentResource` | `get(string $taskId)`, `post(array\|StockAdjustmentPostData $body)`, `put(array\|StockAdjustmentPutData $body)`, `delete(string $id, ?bool $void = null)` |
| `$cin7->stockTakeList()` | `StockTakeListResource` | `get($page, $limit, ?StockTakeStatus $status)`, `paginate($limit, ?StockTakeStatus $status): Cin7Paginator` |
| `$cin7->stockTake()` | `StockTakeResource` | `get(string $taskId)`, `post(array\|StockTakePostData $body)`, `put(array\|StockTakePutData $body)`, `delete(string $id, ?bool $void = null)` |
| `$cin7->stockTransferList()` | `StockTransferListResource` | `get($page, $limit, ?StockTransferStatus $status, ?string $search)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->stockTransfer()` | `StockTransferResource` | `get(string $taskId)`, `post(array\|StockTransferPostData $body)`, `put(array\|StockTransferPutData $body)`, `delete(string $id, ?bool $void = null)`; `order()` |
| `$cin7->stockTransfer()->order()` | `StockTransfer\OrderResource` | `get(string $taskId)`, `post(array\|StockTransferOrderPostData $body)` |
| `$cin7->inventoryWriteOffList()` | `InventoryWriteOffListResource` | `get($page, $limit, ?CompletionStatus $status, ?string $search)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->inventoryWriteOff()` | `InventoryWriteOffResource` | `get(string $taskId)`, `post(array\|InventoryWriteOffPostData $body)`, `put(array\|InventoryWriteOffPutData $body)`, `delete(string $id, ?bool $void = null)` |
| `$cin7->transactions()` | `TransactionsResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->product()->attachments()` | `Product\AttachmentsResource` | `get(string $productId)`, `post(array\|ProductAttachmentPostData $body)`, `delete(string $id)` |
| `$cin7->product()->markupPrices()` | `Product\MarkupPricesResource` | `get(string $productId)`, `put(array\|MarkupPricesData $body)` |
| `$cin7->productFamily()` | `ProductFamilyResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|ProductFamilyPostData $body)`, `put(array\|ProductFamilyPutData $body)`; `attachments()` |
| `$cin7->productFamily()->attachments()` | `ProductFamily\AttachmentsResource` | `get(string $familyId)`, `post(array\|ProductFamilyAttachmentPostData $body)`, `delete(string $id)` |
| `$cin7->moneyTask()` | `MoneyTaskResource` | `get(string $taskId)`, `post(array\|MoneyTaskPostData $body)`, `put(array\|MoneyTaskPutData $body)`, `delete(string $id, ?bool $void = null)` |
| `$cin7->sale()` | `SaleResource` | `get(string $id, …)`, `post(array\|SalePostData $body)`, `put(array\|SalePutData $body)`, `delete(string $id, ?bool $void = null)`; `quote()`, `order()`, `fulfilment()`, `invoice()`, `creditNote()`, `payment()`, `manualJournal()`, `attachment()` |
| `$cin7->sale()->fulfilment()` | `Sale\FulfilmentResource` | `get(string $saleId, …)`, `post(array\|SaleFulfilmentsData $body)`, `delete(string $taskId, ?bool $void = null)`; `pick()`, `pack()`, `ship()` |
| `$cin7->sale()->fulfilment()->pick()` | `Sale\Fulfilment\PickResource` | `get(string $taskId, …)`, `post(array\|SaleFulfilmentPickPostData $body)`, `put(array\|SaleFulfilmentPickPutData $body)` |
| `$cin7->sale()->fulfilment()->pack()` | `Sale\Fulfilment\PackResource` | `get(string $taskId, …)`, `post(array\|SaleFulfilmentPackPostData $body)`, `put(array\|SaleFulfilmentPackData $body)` |
| `$cin7->sale()->fulfilment()->ship()` | `Sale\Fulfilment\ShipResource` | `get(string $taskId)`, `post(array\|SaleFulfilmentShipPostData $body)`, `put(array\|SaleFulfilmentShipPutData $body)` |
| `$cin7->purchase()` | `PurchaseResource` | `get(string $id, …)`, `post(array\|PurchasePostData $body)`, `put(array\|PurchasePutData $body)`, `delete(string $id, ?bool $void = null)`; `order()`, `stock()`, `invoice()`, `creditNote()`, `payment()`, `manualJournal()`, `attachment()` |
| `$cin7->purchase()->order()` | `Purchase\OrderResource` | `get(string $taskId, ?bool $combineAdditionalCharges = null)`, `post(array\|PurchaseOrderPostData $body)` |
| `$cin7->purchase()->stock()` | `Purchase\StockResource` | `get(string $taskId)`, `post(array\|PurchaseStockPostData $body)` |
| `$cin7->purchase()->invoice()` | `Purchase\InvoiceResource` | `get(string $taskId, …)`, `post(array\|PurchaseInvoicePostData $body)` |
| `$cin7->purchase()->creditNote()` | `Purchase\CreditNoteResource` | `get(string $taskId, …)`, `post(array\|PurchaseCreditNotePostData $body)` |
| `$cin7->purchase()->payment()` | `Purchase\PaymentResource` | `get(string $taskId)`, `post(array\|PurchasePaymentPostData $body)`, `put(array\|PurchasePaymentPutData $body)`, `delete(string $id, ?bool $deleteAllocation = null)` |
| `$cin7->purchase()->manualJournal()` | `Purchase\ManualJournalResource` | `get(string $taskId)`, `post(array\|PurchaseManualJournalPostData $body)` |
| `$cin7->purchase()->attachment()` | `Purchase\AttachmentResource` | `get(string $taskId)`, `post(array\|PurchaseAttachmentPostData $body)`, `delete(string $id)` |
| `$cin7->advancedSale()` | `AdvancedSaleResource` | `get(string $id, …)`, `post(array\|AdvancedSalePostData $body)`, `put(array\|SalePutData $body)`, `delete(string $id, ?bool $void = null)`; `fulfilment()`, `invoice()`, `creditNote()`, `payment()`, `manualJournal()`, which are `sale`'s |
| `$cin7->advancedPurchase()` | `AdvancedPurchaseResource` | `get(string $id, …)`, `post(array\|AdvancedPurchasePostData $body)`, `put(array\|AdvancedPurchasePutData $body)`, `delete(string $id, ?bool $void = null)`; `stock()`, `putAway()`, `invoice()`, `creditNote()`, `payment()`, `manualJournal()` |
| `$cin7->advancedPurchase()->stock()` | `AdvancedPurchase\StockResource` | `get(string $purchaseId)`, `post(array\|AdvancedPurchaseStockPostData $body)`, `put(array\|AdvancedPurchaseStockPutData $body)`, `delete(string $taskId, ?bool $void = null)` |
| `$cin7->advancedPurchase()->putAway()` | `AdvancedPurchase\PutAwayResource` | `get(string $purchaseId)`, `post(array\|AdvancedPurchasePutAwayPostData $body)` |
| `$cin7->advancedPurchase()->invoice()` | `AdvancedPurchase\InvoiceResource` | `get(string $purchaseId, ?bool $combineAdditionalCharges = null)`, `post(array\|AdvancedPurchasePartialInvoicePostData $body)`, `delete(string $taskId, ?bool $void = null)` |
| `$cin7->advancedPurchase()->creditNote()` | `AdvancedPurchase\CreditNoteResource` | `get(string $purchaseId, …)`, `post(array\|AdvancedPurchasePartialCreditNotePostData $body)`, `delete(string $taskId)` |
| `$cin7->advancedPurchase()->payment()` | `AdvancedPurchase\PaymentResource` | `get(?string $purchaseId = null, …)`, `post(array\|AdvancedPurchasePaymentPostData $body)`, `put(array\|AdvancedPurchasePaymentPutData $body)`, `delete(string $id, ?bool $deleteAllocation = null)` |
| `$cin7->advancedPurchase()->manualJournal()` | `AdvancedPurchase\ManualJournalResource` | `get(string $purchaseId)`, `post(array\|AdvancedPurchasePartialManualJournalPostData $body)` |
| `$cin7->moneyTaskList()` | `MoneyTaskListResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->saleList()` | `SaleListResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->saleCreditNoteList()` | `SaleCreditNoteListResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->purchaseList()` | `PurchaseListResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->purchaseCreditNoteList()` | `PurchaseCreditNoteListResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->ref()` | `RefResource` | `tax()`, `customer()`, `supplier()`, `account()`, `attributeSet()`, `priceTier()`, `productAvailability()`, `brand()`, `category()`, `unit()`, `location()`, `carrier()`, `templates()`, `fixedAssetType()`, `paymentTerm()`; a pure grouping, as V2 has no action on `/ref` |
| `$cin7->ref()->tax()` | `Ref\TaxResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|TaxPostData $body)`, `put(array\|TaxPutData $body)` |
| `$cin7->ref()->customer()` | `Ref\CustomerResource` | `credits()`; also a pure grouping |
| `$cin7->ref()->customer()->credits()` | `Ref\Customer\CreditsResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->ref()->supplier()` | `Ref\SupplierResource` | `deposits()`; also a pure grouping |
| `$cin7->ref()->supplier()->deposits()` | `Ref\Supplier\DepositsResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->ref()->account()` | `Ref\AccountResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|AccountPostData $body)`, `put(array\|AccountPutData $body)`, `delete(string $code)`; `bank()` |
| `$cin7->ref()->account()->bank()` | `Ref\Account\BankResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->ref()->productAvailability()` | `Ref\ProductAvailabilityResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->ref()->priceTier()` | `Ref\PriceTierResource` | `get()` |
| `$cin7->ref()->attributeSet()` | `Ref\AttributeSetResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|AttributeSetPostData $body)`, `put(array\|AttributeSetPutData $body)`, `delete(string $id)` |
| `$cin7->ref()->brand()` | `Ref\BrandResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|BrandPostData $body)`, `put(array\|BrandPutData $body)`, `delete(string $id)` |
| `$cin7->ref()->category()` | `Ref\CategoryResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|ProductCategoryPostData $body)`, `put(array\|ProductCategoryPutData $body)`, `delete(string $id)` |
| `$cin7->ref()->unit()` | `Ref\UnitResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|UnitOfMeasurePostData $body)`, `put(array\|UnitOfMeasurePutData $body)`, `delete(string $id)` |
| `$cin7->ref()->location()` | `Ref\LocationResource` | `get($page, $limit, $id, $deprecated, $name)`, `paginate($limit, …): Cin7Paginator`, `post(array\|LocationPostData $body)`, `put(array\|LocationPutData $body)`, `delete(string $id)` |
| `$cin7->ref()->carrier()` | `Ref\CarrierResource` | `get($page, $limit, $carrierId, $description)`, `paginate($limit, …): Cin7Paginator`, `post(array\|CarrierPostData $body)`, `put(array\|CarrierPutData $body)`, `delete(string $id)` |
| `$cin7->ref()->templates()` | `Ref\TemplatesResource` | `get($page, $limit, $type, $name)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->ref()->customer()->templates()` | `Ref\Customer\TemplatesResource` | `get($page, $limit, $customerId)`, `paginate($limit, $customerId): Cin7Paginator`, `post(array\|CustomerDefaultTemplatesPostData $body)`, `delete(string $templateId, string $customerId)` |
| `$cin7->ref()->fixedAssetType()` | `Ref\FixedAssetTypeResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|FixedAssetTypePostData $body)`, `put(array\|FixedAssetTypePutData $body)` |
| `$cin7->ref()->paymentTerm()` | `Ref\PaymentTermResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|PaymentTermPostData $body)`, `put(array\|PaymentTermPutData $body)`, `delete(string $id)` |

`…` stands for the optional query parameters, listed per request in
[query parameters](requests.md#query-parameters).

## Customer

`customer` has no GUID-keyed find: `get(id: $guid)` filters the list like any other
filter, because V2 answers `customer?ID=…` with the same `{Total, Page, CustomerList}` envelope
as an unfiltered list. The other filters are `name`, `modifiedSince`, `includeDeprecated`,
`includeProductPrices` and `contactFilter`.

```php
$match = $this->cin7->customer()->get(id: $guid)->json('CustomerList')[0] ?? null;
```

`get()->dto()` is a `list<CustomerData>`, with `Addresses` (`CustomerAddressData`), `Contacts`
(`CustomerContactData`), `ProductPrices` (`ProductPriceData`) and `ChildCustomers`
(`ChildCustomerData`). `post()` takes a `CustomerPostData` and `put()` a `CustomerPutData` as well
as an array (see [data](data.md)), and both answer with the saved customer: their `dto()` is a
`CustomerData`.

```php
$customers = $this->cin7->customer()->get(id: $guid)->dto(); // list<CustomerData>

$saved = $this->cin7->customer()->post(CustomerPostData::from([
    'Name' => 'ACME',
    'Status' => 'Active',
    'Currency' => 'GBP',
    'PaymentTerm' => '30 days',
    'AccountReceivable' => '610',
    'RevenueAccount' => '200',
    'TaxRule' => 'Tax Exempt',
    'Addresses' => [['Line1' => '1 High St', 'Country' => 'UK', 'Type' => 'Billing']],
]))->dto(); // CustomerData
```

## Supplier

`supplier` is the customer's twin: no GUID-keyed find, so `get(id: $guid)` filters the
`{Total, Page, SupplierList}` list, with `name`, `modifiedSince` and `includeDeprecated` the
other filters. `get()->dto()` is a `list<SupplierData>`, with `Addresses`
(`CustomerAddressData`) and `Contacts` (`CustomerContactData`), the models the customer shares.
`post()` takes a `SupplierPostData` and `put()` a `SupplierPutData`, which requires `ID`, as well
as an array, and both answer with the saved supplier: their `dto()` is a `SupplierData`.

```php
use Ipsocode\Cin7\Data\Supplier\SupplierPostData;

$suppliers = $this->cin7->supplier()->get(name: 'Bayside')->dto(); // list<SupplierData>

$saved = $this->cin7->supplier()->post(SupplierPostData::from([
    'Name' => 'Bayside Club',
    'Currency' => 'AUD',
    'PaymentTerm' => '30 days',
    'AccountPayable' => '800',
    'TaxRule' => 'BAS Excluded',
    'Contacts' => [['Name' => 'Bob Partridge', 'Default' => true]],
]))->dto(); // SupplierData
```

## Me

`$cin7->me()` is `me`, the company the API application belongs to. `get()` takes no parameters,
and its `dto()` is a `MeData`: the company's name, base currency and time zone, its default units
and sale tax rule, its lock and opening balance dates, and how it applies discounts and rounds
prices (`RoundingTable`, a list of `RoundingTableData`).

```php
use Ipsocode\Cin7\Enums\WeightUnit;

$me = $this->cin7->me()->get()->dto(); // MeData

$inGrams = $me->DefaultWeightUnits === WeightUnit::Gram;
```

`$cin7->me()->addresses()` is `me/addresses`, the company's addresses. It lists under
`MeAddressesList` (`{Total, Page, MeAddressesList}`), filtered by `id`, `type` (an `AddressType`),
`defaultForType`, `country`, `stateProvince` and `citySuburb`, and `get()->dto()` is a
`list<MeAddressData>`. `post()` takes a `MeAddressPostData` and `put()` a `MeAddressPutData`,
which requires `AddressID`, as well as an array, and both answer with the saved address: their
`dto()` is a `MeAddressData`. `delete($id)` sends `me/addresses?ID=…` and answers `{Success}`.

```php
use Ipsocode\Cin7\Data\Me\Addresses\MeAddressPostData;
use Ipsocode\Cin7\Enums\AddressType;

$billing = $this->cin7->me()->addresses()->get(type: AddressType::Billing, defaultForType: true)->dto(); // list<MeAddressData>

$saved = $this->cin7->me()->addresses()->post(MeAddressPostData::from([
    'Line1' => '1 High St',
    'CitySuburb' => 'London',
    'StateProvince' => 'Greater London',
    'ZipPostCode' => 'EC1A 1AA',
    'Country' => 'United Kingdom',
    'Type' => 'Business',
]))->dto(); // MeAddressData

$this->cin7->me()->addresses()->delete($addressId); // DELETE me/addresses?ID=…
```

`$cin7->me()->contacts()` is its twin for the company's contacts, on `me/contacts`: it lists under
`MeContactsList`, filtered by `id`, `name` (contacts whose name starts with it), `type` (a
`ContactType`), `defaultForType`, `phone`, `fax` and `email`, and `get()->dto()` is a
`list<MeContactData>`. `post()` takes a `MeContactPostData` and `put()` a `MeContactPutData`, which
requires `ContactID`; both answer with the saved `MeContactData`, and `delete($id)` with
`{Success}`.

```php
use Ipsocode\Cin7\Enums\ContactType;

foreach ($this->cin7->me()->contacts()->paginate(type: ContactType::Employee)->items() as $contact) {
    // $contact is one entry of MeContactsList
}
```

## Product

`product` lists under `Products`, not `ProductList`: its envelope is
`{Total, Page, Products}`, and `GetProduct` declares that key, so
`$cin7->product()->paginate()` yields every product. The V2 list filters are named
arguments of `get()` and `paginate()` (`id`, `name`, `sku`, `modifiedSince`,
`includeDeprecated`, `includeBom`, …); booleans go out as `true`/`false`.

```php
foreach ($this->cin7->product()->paginate(includeDeprecated: false)->items() as $product) {
    // $product is one entry of Products
}
```

`get()->dto()` is a `list<ProductData>`, with `Suppliers` (`ProductSupplierData`, whose
`ProductSupplierOptions` hold `SupplyIntervals`), `ReorderLevels` (`ReorderLevelData`),
`BillOfMaterialsProducts` (`BillOfMaterialProductData`), `BillOfMaterialsServices`
(`BillOfMaterialServiceData`), `Movements` (`ProductMovementData`), `Attachments`
(`AttachmentLineData`) and `CustomPrices` (`ProductPriceData`). `PriceTiers` is a plain
`array<string, float>` keyed by the account's tier names. `post()` takes a `ProductPostData` and
`put()` a `ProductPutData` as well as an array, and their `dto()` is the saved `ProductData`.

```php
$product = $this->cin7->product()->get(id: $guid)->dto()[0]; // ProductData

$saved = $this->cin7->product()->put(ProductPutData::from([
    ...$product->toArray(),
    'PriceTiers' => ['Tier 1' => 8.0],
]))->dto(); // ProductData
```

## Ref

The reference data lives under `ref/…`, so the chain spells the path:
`$cin7->ref()->tax()`, `$cin7->ref()->customer()->credits()`,
`$cin7->ref()->supplier()->deposits()`, `$cin7->ref()->account()` and
`$cin7->ref()->account()->bank()`.

`ref/tax` lists under `TaxRuleList` (`{Total, Page, TaxRuleList}`). Its data classes are
`TaxData` and `TaxComponentData`; `get()->dto()` is a `list<TaxData>`. `post()` takes a
`TaxPostData` and `put()` a `TaxPutData`, which requires the rule's `ID`, and both return the saved
`TaxData` from `dto()` (see [data](data.md)). Its V2 filters
are named arguments of `get()` and `paginate()`: `id`, `name`, `isActive`, `isTaxForSale`,
`isTaxForPurchase` and `account`.

`ref/customer/credits` lists under `CustomerCredits` (`get()->dto()` is a
`list<CustomerCreditData>`) and its envelope has no `Total`;
see [pagination](pagination.md#an-envelope-with-no-total). Its filters are `customerId` and
`showUsedCredits`. `ref/supplier/deposits` is its twin for suppliers: it lists under
`SupplierDeposits` (`get()->dto()` is a `list<SupplierDepositData>`), has no `Total` either, and
filters by `supplierId` and `showUsedDeposits`.

```php
$vat = $this->cin7->ref()->tax()->get(isActive: true)->json('TaxRuleList');

foreach ($this->cin7->ref()->customer()->credits()->paginate(customerId: $guid)->items() as $credit) {
    // $credit is one entry of CustomerCredits
}

foreach ($this->cin7->ref()->supplier()->deposits()->paginate(supplierId: $guid)->items() as $deposit) {
    // $deposit is one entry of SupplierDeposits
}
```

`ref/account` is the chart of accounts. It lists under `AccountsList`
(`{Total, Page, AccountsList}`), filtered by `code`, `name` (accounts whose name starts with it),
`type` and `status`, and `get()->dto()` is a `list<AccountData>`. `post()` takes an
`AccountPostData` and `put()` an `AccountPutData` as well as an array; the account's `Code` names
it, and both answer with the saved `AccountData`. Cin7 refuses a PUT while the Xero or QuickBooks
integration is on. `delete($code)` sends `ref/account?Code=…` and answers `{Success}`.

```php
use Ipsocode\Cin7\Data\Ref\Account\AccountPostData;

$banks = $this->cin7->ref()->account()->get(type: 'BANK')->dto(); // list<AccountData>

$saved = $this->cin7->ref()->account()->post(AccountPostData::from([
    'Code' => '091',
    'Name' => 'Savings Account',
    'Type' => 'BANK',
    'Status' => 'ACTIVE',
    'Bank' => 'Bank of Example',
    'BankAccountNumber' => '12345678',
]))->dto(); // AccountData

$this->cin7->ref()->account()->delete('091'); // DELETE ref/account?Code=091
```

`ref/account/bank` lists the bank accounts under `BankAccountsList`, filtered by `id`, `name`
(bank accounts whose name starts with it) and `bank`; `get()->dto()` is a
`list<BankAccountData>`. It is read-only.

```php
foreach ($this->cin7->ref()->account()->bank()->paginate()->items() as $bankAccount) {
    // $bankAccount is one entry of BankAccountsList
}
```

`ref/brand`, `ref/category` (the product categories) and `ref/unit` (the units of measure) are the
same simple resource three times: they list under `BrandList`, `CategoryList` and `UnitList`,
filtered by `name`, and `post()` and `put()` take a `…PostData` or `…PutData` (which requires `ID`)
or an array. Unlike `ref/tax`, a POST or PUT answers with the saved record, not a list, so its
`dto()` is a `BrandData`, `ProductCategoryData` or `UnitOfMeasureData`; `delete($id)` sends `?ID=…`.

```php
$brand = $this->cin7->ref()->brand()->post(['Name' => 'Acme'])->dto(); // BrandData

$this->cin7->ref()->unit()->delete($unitId);
```

`ref/fixedassettype` lists under `FixedAssetTypeList`, filtered by `fixedAssetTypeId` and `name`;
`post()` takes a `FixedAssetTypePostData` and `put()` a `FixedAssetTypePutData`, which requires
`FixedAssetTypeID`, as well as an array, and `get()->dto()` is a `list<FixedAssetTypeData>`. Set
`Rate` or `EffectiveLife`, not both.

`ref/paymentterm` lists under `PaymentTermList`, filtered by `id`, `name`, `method` (a
`PaymentTermMethod`), `isActive` and `isDefault`; `post()` takes a `PaymentTermPostData` and `put()`
a `PaymentTermPutData`, which requires `ID`; `delete($id)` sends `ref/paymentterm?ID=…`.

```php
use Ipsocode\Cin7\Enums\PaymentTermMethod;

$terms = $this->cin7->ref()->paymentTerm()->get(method: PaymentTermMethod::NumberOfDays, isActive: true)->dto(); // list<PaymentTermData>
```

## Bank transfer

`$cin7->bankTransfer()` is `bankTransfer`, a transfer between two bank accounts, and the Money
Task's twin: keyed by `TaskID` (`get($taskId)`), with no list action, since `moneyTaskList` lists
the transfers. `post()` takes a `BankTransferPostData` and `put()` a `BankTransferPutData`, which
requires `TaskID`, as well as an array. Every action answers with the transfer, so `dto()` is a
`BankTransferData`, with `Transactions` (`TransactionStockLineData`) and `Attachments`
(`AttachmentLineData`). `delete($id, void: true)` voids it. Cin7 works out the
`CurrencyConversionRate` itself.

```php
$transfer = $this->cin7->bankTransfer()->post(BankTransferPostData::from([
    'Status' => 'DRAFT',
    'FromAccount' => '198489',
    'ToAccount' => '713',
    'FromAmount' => 3,
    'ToAmount' => 6,
    'Date' => '2018-01-17T00:00:00',
]))->dto(); // BankTransferData
```

## Journal and transactions

`$cin7->journal()` is `journal`, the manual journals. It lists under `Journals`, filtered by
`taskId`, `status` (a `CompletionStatus`) and `search`, and `get()->dto()` is a `list<JournalData>`
with `Lines` (`JournalLineData`) and `Attachments` (`AttachmentLineData`). `post()` takes a
`JournalPostData` and `put()` a `JournalPutData`, which requires `TaskID`, as well as an array;
both, and `delete()`, answer with the journal, so their `dto()` is a `JournalData`.
`delete($id, void: true)` voids the journal and `void: false` undoes the void.

`$cin7->transactions()` is read-only: it lists the ledger's transactions under `Transactions`,
filtered by `fromDate`, `toDate` and the debit or credit `account` code, as `TransactionData`.

```php
$journal = $this->cin7->journal()->post(JournalPostData::from([
    'Status' => 'DRAFT',
    'Currency' => 'USD',
    'CurrencyConversionRate' => 50,
    'EffectiveDate' => '2018-01-20T00:00:00',
    'Lines' => [['Debit' => '260', 'Credit' => '270', 'Amount' => 2, 'BaseAmount' => 100]],
]))->dto(); // JournalData

$this->cin7->journal()->delete($journal->TaskID, void: true);

foreach ($this->cin7->transactions()->paginate(account: '610')->items() as $transaction) {
    // $transaction is one entry of Transactions
}
```

## Product attachments and availability

`$cin7->product()->attachments()` is `product/attachments`. `get($productId)`, `post()` and
`delete($id)` each answer a bare `list<AttachmentLineData>`. `post()` takes a
`ProductAttachmentPostData` or an array: a file as base64 `Content`, or a `FileDownloadUrl` Cin7
fetches, with `IsDefault` to make an image the default one.

`$cin7->ref()->productAvailability()` lists each product's stock by location, bin and batch under
`ProductAvailabilityList`, filtered by `id`, `name`, `sku`, `location`, `batch` and `category`, as
`ProductAvailabilityData`.

```php
$this->cin7->product()->attachments()->post([
    'ProductID' => $productId,
    'FileName' => 'front.jpg',
    'FileDownloadUrl' => 'https://files.example/front.jpg',
    'IsDefault' => true,
]);

foreach ($this->cin7->ref()->productAvailability()->paginate(location: 'Main Warehouse')->items() as $stock) {
    // $stock is one entry of ProductAvailabilityList
}
```

## Attribute sets

`$cin7->ref()->attributeSet()` lists under `AttributeSetList`, filtered by `id` and `name`, as
`AttributeSetData` with ten attributes (`Attribute1Name` to `Attribute10Values`, and the read-only
`Attributes`). A write needs the first attribute: `post()` takes an `AttributeSetPostData` and
`put()` an `AttributeSetPutData`, which also requires `ID`, or an array, and answers with the saved
set itself; `delete($id)` sends `?ID=…`.

```php
$set = $this->cin7->ref()->attributeSet()->post(AttributeSetPostData::from([
    'Name' => 'Clothing',
    'Attribute1Name' => 'Colour',
    'Attribute1Type' => 'List',
    'Attribute1Values' => 'Red, Black, Blue',
]))->dto(); // AttributeSetData
```

## Product family

`$cin7->productFamily()` lists under `ProductFamilies`, filtered by `id`, `name`, `sku` and
`modifiedSince`, and `get()->dto()` is a `list<ProductFamilyData>`, with `Products`
(`ProductFamilyProductLineData`) and `Attachments`. `post()` takes a `ProductFamilyPostData` and
`put()` a `ProductFamilyPutData`, which requires `ID`, as well as an array; both answer with the
saved family. A PUT adds or updates the products it lists and never deletes one.
`$cin7->productFamily()->attachments()` is the product's attachments for a family: `get($familyId)`,
`post()` and `delete($id)`.

```php
$family = $this->cin7->productFamily()->get(sku: 'GB1')->dto()[0]; // ProductFamilyData

$this->cin7->productFamily()->attachments()->post([
    'FamilyID' => $family->ID,
    'FileName' => 'front.jpg',
    'FileDownloadUrl' => 'https://files.example/front.jpg',
]);
```

## Price tiers and markup prices

`$cin7->ref()->priceTier()->get()` lists the account's price tiers, by code (1 to 10) and name, as
`list<PriceTierData>`. They cannot be paged. A product's own `PriceTiers` is a different thing: a
map of those names to prices.

`$cin7->product()->markupPrices()` is `product/markupprices`. `get($productId)` answers a
`MarkupPricesData`, with a `MarkupPriceLineData` for each of the ten tiers: a tier with no markup
has `MarkupType::Deleted`. `put()` takes a `MarkupPricesData` or an array: a line for a tier
creates or changes its markup, and a `Deleted` line deletes it.

```php
use Ipsocode\Cin7\Data\Product\MarkupPrices\MarkupPricesData;

$this->cin7->product()->markupPrices()->put(MarkupPricesData::from([
    'ProductID' => $productId,
    'MarkupPrices' => [
        ['TierNumber' => 1, 'MarkupType' => 'P', 'UsePriceType' => 'A', 'MarkupValue' => 20],
        ['TierNumber' => 3, 'MarkupType' => 'D'],
    ],
]));
```

## Money Task

`$cin7->moneyTask()` is the Money Task, on `moneyOperation`. It is keyed by `TaskID`:
`get($taskId)` sends `moneyOperation?TaskID=…`. V2 marks
`TaskID` optional on GET, but the package requires it, because the list lives at
`moneyTaskList`, which is `$cin7->moneyTaskList()` (`get()` and `paginate()`, filtered by
`status`, a `CompletionStatus`, `search` and `taskType`, a `MoneyTaskType`, and answering a
`list<MoneyTaskListData>`). `delete($id, void: true)` sends `moneyOperation?ID=…&Void=true` and
voids the task, and `void: false` undoes a void. Without `void` no `Void` is sent, and the
reference defaults it to `false`. Every action answers with the Money Task, so `dto()` is a
`MoneyTaskData` for `get()`, `post()`, `put()` and `delete()`, with `Lines` (`MoneyTaskLineData`),
`Transactions` (`TransactionStockLineData`) and `Attachments` (`AttachmentLineData`). `post()`
takes a `MoneyTaskPostData` and `put()` a `MoneyTaskPutData`, which requires `TaskID`, as well as
an array (see [data](data.md)).

```php
$task = $this->cin7->moneyTask()->get($taskId)->dto(); // MoneyTaskData

$this->cin7->moneyTask()->post(MoneyTaskPostData::from([
    'TaskType' => 'Receive Money',
    'Status' => 'DRAFT',
    'BankAccount' => '198489',
    'Date' => '2018-01-17T00:00:00',
]));
$this->cin7->moneyTask()->delete($taskId, void: true);
```

## Sale

`sale` is keyed: `get($id)` sends `sale?ID=…`, and the optional V2 parameters are named
arguments: `combineAdditionalCharges`, `hideInventoryMovements`, `includeTransactions` and
`countryFormat`, a `CountryFormat`. `sale` has no list action, so a `GetSale` cannot be
paginated; list sales through `saleList()`, whose envelope is `{Total, Page, SaleList}` and
whose filters are named arguments of `get()` and `paginate()`: `search`, the dates
(`createdSince`, `updatedSince`, `updatedUntil`, `shipBy`), the statuses, each typed with its
enum (`status` is a `SaleStatus`), `externalId`, `readyForShipping` and `orderLocationId`.

`delete($id, void: true)` sends `sale?ID=…&Void=true` and voids the sale, and `void: false`
undoes a void; without `void` no `Void` is sent, and the reference defaults it to `false`.
Every `sale` action answers with the Sale, so `dto()` is a `SaleData`
for `get()`, `post()`, `put()` and `delete()`, and a `list<SaleListData>` for
`saleList()->get()`. `post()` and `put()` accept a `SalePostData` and a `SalePutData` as well as an array (see
[data](data.md)).

```php
use Ipsocode\Cin7\Enums\SaleStatus;

$sale = $this->cin7->sale()->get($guid, includeTransactions: true)->dto(); // SaleData

foreach ($this->cin7->saleList()->paginate(status: SaleStatus::Ordered)->items() as $row) {
    // $row is one entry of SaleList
}

$this->cin7->sale()->delete($guid, void: true);
```

### Sale quote, order, invoice, credit note, payment, manual journal and attachment

`sale` also has these sub-resources, mirroring the V2 paths: `$cin7->sale()->quote()`,
`->order()`, `->invoice()`, `->creditNote()`, `->payment()`, `->manualJournal()` and
`->attachment()`, and `->fulfilment()` (see [sale fulfilment](#sale-fulfilment)). Their `get()`
takes the sale's GUID (`SaleID`), then the optional parameters as named arguments; they send
`sale/quote`, `sale/order`, `sale/invoice`, `sale/creditnote`, `sale/payment`,
`sale/manualJournal` and `sale/attachment`.

| Resource | Methods | Body and `dto()` |
|---|---|---|
| `sale()->quote()` (Sale Quote Model and table) | `get(string $saleId, ?bool $combineAdditionalCharges = null, ?bool $includeProductInfo = null)`, `post(array\|SaleQuotePostData $body)` | `SaleQuoteData` |
| `sale()->order()` (Sale Order Model) | `get(string $saleId, ?bool $combineAdditionalCharges = null, ?bool $includeProductInfo = null)`, `post(array\|SaleOrderData $body)` | `SaleOrderData` |
| `sale()->invoice()` (Sale Invoice Partial and POST Models) | `get($saleId, …)`, `post(array\|SaleInvoicePostData)`, `put(array\|SaleInvoicePutData)`, `delete(string $taskId, ?bool $void = null)` | `SaleInvoicesData`, the `{SaleID, Invoices}` envelope |
| `sale()->creditNote()` (Sale Credit Note Partial and POST Models) | `get($saleId, …)` (also `includePaymentInfo`), `post(array\|SaleCreditNotePostData)`, `delete(string $taskId, ?bool $void = null)` | `SaleCreditNotesData`, the `{SaleID, CreditNotes}` envelope |
| `sale()->payment()` (Sale Payment Line Partial Model) | `get(string $saleId)`, `post(array\|SalePaymentPostData)`, `put(array\|SalePaymentPutData)`, `delete(string $id)` | `GET`: `list<SalePaymentLinePartialData>`; POST, PUT: one line; DELETE answers `{Success}` |
| `sale()->manualJournal()` (Sale Manual Journal Model and table) | `get(string $saleId)`, `post(array\|SaleManualJournalPostData)` | `SaleManualJournalData` |
| `sale()->attachment()` (Sale Attachments) | `get(string $saleId)`, `post(array\|SaleAttachmentPostData)`, `delete(string $id)` | `SaleAttachmentsData`, the `{SaleID, Lines}` envelope |

Invoice and credit note deletes go by `TaskID` and take `Void`; a payment delete goes by `ID`
and has no `Void`. An invoice or credit note POST with the empty GUID as `TaskID`
(`00000000-0000-0000-0000-000000000000`) creates a new one. The POST and PUT bodies are per verb,
each with the fields the reference requires for that verb mandatory: a payment POST needs
`TaskID`, `Type`, `Amount`, `DatePaid`, `Account` and `CurrencyRate`, a payment PUT only `ID`, an
invoice PUT only `SaleID` and `TaskID`. A payment read with `get()` becomes a PUT body with
`SalePaymentPutData::from($payment->toArray())`, which keeps the fields PUT takes. A quote POST
needs `SaleID`, `CombineAdditionalCharges`, `Memo`, a `Status` of `DRAFT` or `AUTHORISED` and
`Lines`, but no totals; an attachment POST needs base64 `Content` or a `FileDownloadUrl`.

`$cin7->saleCreditNoteList()` lists the sales with a credit note, like `saleList()`, from the same
`{Total, Page, SaleList}` envelope; its `get()` and `paginate()` filter by `search`, the dates,
`creditNoteStatus` and `status`, and `dto()` is a `list<SaleCreditNoteListData>`.

```php
$this->cin7->sale()->invoice()->delete($taskId, void: true); // DELETE sale/invoice?TaskID=…&Void=true
$payments = $this->cin7->sale()->payment()->get($saleId)->dto(); // list<SalePaymentLinePartialData>
```

### Sale fulfilment

`$cin7->sale()->fulfilment()` is `sale/fulfilment`, a sale's fulfilments, and each fulfilment's
stages are its own resources below it, as the paths are: `->pick()`, `->pack()` and `->ship()`
send `sale/fulfilment/pick`, `/pack` and `/ship`. A fulfilment is read by the sale's GUID, its
stages by the fulfilment's `TaskID`.

| Resource | Methods | Body and `dto()` |
|---|---|---|
| `sale()->fulfilment()` (Sale Fulfilment) | `get(string $saleId, ?bool $includeProductInfo = null)`, `post(array\|SaleFulfilmentsData)`, `delete(string $taskId, ?bool $void = null)` | `SaleFulfilmentsData`, the `{SaleID, Fulfilments}` envelope; POST needs only the `SaleID` |
| `sale()->fulfilment()->pick()` (Sale Fulfilment Pick) | `get(string $taskId, ?bool $includeProductInfo = null)`, `post(array\|SaleFulfilmentPickPostData)`, `put(array\|SaleFulfilmentPickPutData)` | `SaleFulfilmentPickData`, `{TaskID, Status, Lines}` |
| `sale()->fulfilment()->pack()` (Sale Fulfilment Pack) | `get(string $taskId, ?bool $includeProductInfo = null)`, `post(array\|SaleFulfilmentPackPostData)`, `put(array\|SaleFulfilmentPackData)` | `SaleFulfilmentPackData`, `{TaskID, Status, Lines}` |
| `sale()->fulfilment()->ship()` (Sale Fulfilment Ship) | `get(string $taskId)`, `post(array\|SaleFulfilmentShipPostData)`, `put(array\|SaleFulfilmentShipPutData)` | `SaleFulfilmentShipData`, the shipment with its `TaskID` |

A POST creates a pick, pack or shipment or adds lines to it, and a PUT replaces one that is not
authorised. `AutoPickMode: 'AUTOPICK'` in place of a `Status` picks the task automatically, and
`AddTrackingNumbers: true` lets a shipment PUT change the tracking numbers or carrier of an
authorised shipment; the reference documents both in prose only. A shipment line names its box
`Box` in a body and `Boxes` in a response.

```php
use Ipsocode\Cin7\Data\Sale\Fulfilment\Pick\SaleFulfilmentPickPostData;
use Ipsocode\Cin7\Data\Sale\Fulfilment\SaleFulfilmentsData;

$fulfilments = $this->cin7->sale()->fulfilment()->post(SaleFulfilmentsData::from(['SaleID' => $saleId]))->dto();
$taskId = $fulfilments->Fulfilments[0]->TaskID;

$this->cin7->sale()->fulfilment()->pick()->post(SaleFulfilmentPickPostData::from([
    'TaskID' => $taskId,
    'AutoPickMode' => 'AUTOPICK',
]));
```

## Purchase

`$cin7->purchase()` is `purchase`, the simple purchase, which the reference marks deprecated: it
supports only simple purchases, and an advanced purchase is on `advanced-purchase`. `purchase` is
keyed: `get($id)` sends `purchase?ID=…`, and `combineAdditionalCharges: true` lists the additional
charges in `Lines`. `post()` takes a `PurchasePostData` and `put()` a `PurchasePutData` as well as
an array; a write needs `Approach` (`INVOICE` or `STOCK`), `Location` and the supplier, by
`Supplier` or `SupplierID`, and a PUT the purchase's `ID`. `delete($id, void: true)` sends
`purchase?ID=…&Void=true` and voids the purchase, and `void: false` undoes a void; without `void` no
`Void` is sent, and the reference defaults it to `false`. Every action answers with the purchase, so
`dto()` is a `PurchaseData`, which carries its `Order`, `StockReceived`, `Invoice`, `CreditNote` and
`ManualJournals`. `purchase` has no list action; list purchases through `purchaseList()` (see
below).

```php
use Ipsocode\Cin7\Data\Purchase\PurchasePostData;

$purchase = $this->cin7->purchase()->post(PurchasePostData::from([
    'Supplier' => 'ABPA',
    'Approach' => 'INVOICE',
    'Location' => 'Main Warehouse',
]))->dto(); // PurchaseData

$order = $this->cin7->purchase()->get($purchase->ID)->dto()->Order; // PurchaseOrderData

$this->cin7->purchase()->delete($purchase->ID, void: true); // DELETE purchase?ID=…&Void=true
```

`$cin7->purchaseList()` is `purchaseList`, the purchases, simple, advanced and service ones, listed
under `PurchaseList` (`{Total, Page, PurchaseList}`). Its filters are named arguments of `get()` and
`paginate()`: `search`, the dates (`requiredBy`, `updatedSince`, `updatedUntil`), the documents'
statuses (`orderStatus`, `restockReceivedStatus`, `creditNoteStatus` and `unstockStatus`, each a
`TaskStatus`, and `invoiceStatus`, an `InvoiceStatus`), `status`, a string (see
[data](data.md#where-the-references-tables-and-examples-disagree)), and `dropShipTaskId`, the sale
task a drop-ship purchase was created by. `$cin7->purchaseCreditNoteList()` is
`purchaseCreditNoteList`, the purchases with a credit note, from the same `PurchaseList` envelope,
filtered by `search`, `updatedSince`, `updatedUntil`, `creditNoteStatus` and `status`. Their `dto()`
is a `list<PurchaseListData>` and a `list<PurchaseCreditNoteListData>`.

```php
use Ipsocode\Cin7\Enums\InvoiceStatus;

foreach ($this->cin7->purchaseList()->paginate(invoiceStatus: InvoiceStatus::Paid)->items() as $row) {
    // $row is one entry of PurchaseList
}

$credited = $this->cin7->purchaseCreditNoteList()->get(updatedSince: '2021-09-01T00:00:00')->dto(); // list<PurchaseCreditNoteListData>
```

The purchase's documents are sub-resources below `$cin7->purchase()`, as the paths are:
`->order()`, `->stock()`, `->invoice()`, `->creditNote()`, `->payment()`, `->manualJournal()` and
`->attachment()` send `purchase/order`, `purchase/stock`, `purchase/invoice`, `purchase/creditnote`,
`purchase/payment`, `purchase/manualJournal` and `purchase/attachment`. Each is read by the
purchase's `TaskID`, the purchase's `ID`.

`$cin7->purchase()->order()` is `purchase/order`, a purchase's order. `get($taskId)` sends
`purchase/order?TaskID=…`, and `combineAdditionalCharges: true` lists the additional charges in
`Lines`; its `dto()` is a `PurchaseOrderData`, with `Lines` (`PurchaseOrderLineData`) and
`AdditionalCharges` (`PurchaseAdditionalChargeData`). `post()` takes a `PurchaseOrderPostData` as
well as an array and answers with the saved order, a `PurchaseOrderData`. An order POST needs
`TaskID`, `CombineAdditionalCharges`, `Memo`, a `Status` of `DRAFT` or `AUTHORISED` and `Lines`,
but no totals; Cin7 rejects it when the order is neither `DRAFT` nor `NOT AVAILABLE`.

```php
use Ipsocode\Cin7\Data\Purchase\Order\PurchaseOrderPostData;

$order = $this->cin7->purchase()->order()->get($taskId)->dto(); // PurchaseOrderData

$saved = $this->cin7->purchase()->order()->post(PurchaseOrderPostData::from([
    'TaskID' => $taskId,
    'CombineAdditionalCharges' => false,
    'Memo' => '',
    'Status' => 'DRAFT',
    'Lines' => [[
        'ProductID' => $productId,
        'SKU' => 'Bread',
        'Name' => 'Baked Bread',
        'Quantity' => 2.0,
        'Price' => 2.0,
        'Tax' => 0.0,
        'TaxRule' => 'Sales Tax on Imports',
        'Total' => 4.0,
    ]],
]))->dto(); // PurchaseOrderData
```

`$cin7->purchase()->stock()` is `purchase/stock`, a purchase's stock received, which the reference
marks deprecated: it supports only simple purchases, and an advanced purchase's stock is on
`advanced-purchase/stock` and `advanced-purchase/put-away`. `get($taskId)` sends
`purchase/stock?TaskID=…`, and its `dto()` is a `PurchaseStockData`, with `Lines`
(`PurchaseStockLineData`). `post()` takes a `PurchaseStockPostData` as well as an array and answers
with the saved stock received, a `PurchaseStockData`. A stock POST needs `TaskID`, a `Status` of
`DRAFT` or `AUTHORISED` and `Lines`, each with its `Date`, `Quantity` and a `Location` or
`LocationID`; a line's read-only `Name` and `Received` are left out of the body. POST only adds
lines: duplicates in one body become one line with their quantities summed, and a line matching an
existing one's product, location, batch and expiry date is an error. `Status` `AUTHORISED` with
empty `Lines` authorises the stock received. Cin7 rejects the POST unless the order is
`AUTHORISED` and the stock received `DRAFT` or `NOT AVAILABLE`, and, for a purchase whose
`Approach` is `INVOICE`, the invoice `AUTHORISED`.

```php
use Ipsocode\Cin7\Data\Purchase\Stock\PurchaseStockPostData;

$stock = $this->cin7->purchase()->stock()->get($taskId)->dto(); // PurchaseStockData

$saved = $this->cin7->purchase()->stock()->post(PurchaseStockPostData::from([
    'TaskID' => $taskId,
    'Status' => 'DRAFT',
    'Lines' => [[
        'Date' => '2017-12-08T00:00:00',
        'Quantity' => 3.0,
        'SKU' => 'Bread',
        'Location' => 'Main Warehouse',
        'BatchSN' => 'PO-00001-1',
    ]],
]))->dto(); // PurchaseStockData

$this->cin7->purchase()->stock()->post(PurchaseStockPostData::from([
    'TaskID' => $taskId,
    'Status' => 'AUTHORISED',
    'Lines' => [],
])); // authorises the stock received
```

`$cin7->purchase()->invoice()` is `purchase/invoice`, a purchase's invoice, which the reference
marks deprecated: it supports only simple purchases, and an advanced purchase's invoices are on
`advanced-purchase/invoice`. `get($taskId)` sends `purchase/invoice?TaskID=…`, and
`combineAdditionalCharges: true` lists the additional charges in `Lines`. `post()` takes a
`PurchaseInvoicePostData` as well as an array. Both answer with the invoice, so `dto()` is a
`PurchaseInvoiceData`. An invoice POST needs `TaskID`, `CombineAdditionalCharges`, `InvoiceDate`,
`InvoiceDueDate`, a `Status` of `DRAFT` or `AUTHORISED` and `Lines`, but no totals; each line needs
its `Account` and `Total`, and each additional charge its `Account`. Cin7 rejects it unless the order
is `AUTHORISED` and the invoice is `DRAFT` or `NOT AVAILABLE`, and, for a purchase whose `Approach`
is `STOCK`, the stock received is `AUTHORISED`.

```php
use Ipsocode\Cin7\Data\Purchase\Invoice\PurchaseInvoicePostData;

$invoice = $this->cin7->purchase()->invoice()->get($taskId)->dto(); // PurchaseInvoiceData

$this->cin7->purchase()->invoice()->post(PurchaseInvoicePostData::from([
    ...$invoice->toArray(),
    'Status' => 'AUTHORISED',
]));
```

`$cin7->purchase()->creditNote()` is `purchase/creditnote`, a purchase's credit note, which the
reference marks deprecated: it supports only simple purchases, and an advanced purchase's credit
notes are on `advanced-purchase/creditnote`. `get($taskId)` sends `purchase/creditnote?TaskID=…`,
and `combineAdditionalCharges: true` lists the additional charges in `Lines`. `post()` takes a
`PurchaseCreditNotePostData` as well as an array. Both answer with the credit note, so `dto()` is a
`PurchaseCreditNoteData`. A credit note POST needs `TaskID`, `CombineAdditionalCharges`,
`CreditNoteNumber`, `CreditNoteDate`, a `Status` of `DRAFT` or `AUTHORISED`, `Lines` and `Unstock`,
but no totals; each line needs its `Account` and `Total`, each additional charge its `Account`, and
each unstock line its stock batch's `CardID` and `Quantity`. An unstock line's product, location,
batch and expiry are read-only and left out of the body. Cin7 rejects the POST unless the invoice is
`AUTHORISED` or `PAID` and the credit note is `DRAFT` or `NOT AVAILABLE`.

```php
use Ipsocode\Cin7\Data\Purchase\CreditNote\PurchaseCreditNotePostData;

$creditNote = $this->cin7->purchase()->creditNote()->get($taskId)->dto(); // PurchaseCreditNoteData

$this->cin7->purchase()->creditNote()->post(PurchaseCreditNotePostData::from([
    ...$creditNote->toArray(),
    'Status' => 'AUTHORISED',
]));
```

`$cin7->purchase()->payment()` is `purchase/payment`, a purchase's payments, which the reference
marks deprecated: it supports only simple purchases, and an advanced purchase's payments are on
`advanced-purchase/payment`. `get($taskId)` sends `purchase/payment?TaskID=…`, and its `dto()` is a
`list<PurchasePaymentData>`, read from a bare array. `post()` takes a `PurchasePaymentPostData` and
`put()` a `PurchasePaymentPutData` as well as an array; both answer with the saved payment, a
`PurchasePaymentData`. A payment POST needs `TaskID`, `Type`, `Amount`, `DatePaid`, `Account` and
`CurrencyRate`, and takes a `DepositID` to pay from a supplier deposit; a payment PUT needs `TaskID`,
`ID`, `DatePaid` and `CurrencyRate`, and takes no `Amount` or `Account` for a payment from a deposit.
A prepayment cannot be changed. `delete($id)` sends `purchase/payment?ID=…` and answers `{Success}`;
`deleteAllocation` says whether the allocated payments go too. Without it no `DeleteAllocation` is
sent, and the reference defaults it to `true`, so `deleteAllocation: false` keeps them.

```php
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentPostData;

$payments = $this->cin7->purchase()->payment()->get($taskId)->dto(); // list<PurchasePaymentData>

$saved = $this->cin7->purchase()->payment()->post(PurchasePaymentPostData::from([
    'TaskID' => $taskId,
    'Type' => 'Payment',
    'Amount' => 9.0,
    'DatePaid' => '2017-12-21T00:00:00',
    'Account' => '718',
    'CurrencyRate' => 1.0,
]))->dto(); // PurchasePaymentData

$this->cin7->purchase()->payment()->delete($paymentId, deleteAllocation: false); // DELETE purchase/payment?ID=…&DeleteAllocation=false
```

`$cin7->purchase()->manualJournal()` is `purchase/manualJournal`, a purchase's manual journal, which
the reference also marks deprecated: an advanced purchase's manual journals are on
`advanced-purchase/manualJournal`. `get($taskId)` sends `purchase/manualJournal?TaskID=…`, and
`post()` takes a `PurchaseManualJournalPostData` as well as an array; both answer with the manual
journal, a `PurchaseManualJournalData`. A POST needs `TaskID` and a `Status` of `DRAFT` or
`AUTHORISED`, and can be sent even when the journal is authorised. A line's `IsSystem` is read-only
and never sent: a line Cin7 posted (`IsSystem` `true`) cannot be changed or deleted.

```php
use Ipsocode\Cin7\Data\Purchase\ManualJournal\PurchaseManualJournalPostData;

$journal = $this->cin7->purchase()->manualJournal()->post(PurchaseManualJournalPostData::from([
    'TaskID' => $taskId,
    'Status' => 'DRAFT',
    'Lines' => [['Reference' => 'Rounding', 'Amount' => 2.0, 'Date' => '2017-12-06T00:00:00', 'Debit' => '720', 'Credit' => '404']],
]))->dto(); // PurchaseManualJournalData
```

`$cin7->purchase()->attachment()` is `purchase/attachment`, a purchase's attachments, like the
sale's. `get($taskId)` sends `purchase/attachment?TaskID=…`, `post()` takes a
`PurchaseAttachmentPostData` as well as an array, and `delete($id)` sends
`purchase/attachment?ID=…`; each answers with the purchase's attachments, a
`PurchaseAttachmentsData` (`{TaskID, Lines}`, `Lines` being `AttachmentLineData`). A POST needs
the purchase's `PurchaseID`, a `FileName`, and base64 `Content` or a `FileDownloadUrl`.

```php
use Ipsocode\Cin7\Data\Purchase\Attachment\PurchaseAttachmentPostData;

$attachments = $this->cin7->purchase()->attachment()->post(PurchaseAttachmentPostData::from([
    'PurchaseID' => $taskId,
    'FileName' => 'invoice.pdf',
    'FileDownloadUrl' => 'https://files.example/invoice.pdf',
]))->dto(); // PurchaseAttachmentsData

$this->cin7->purchase()->attachment()->delete($attachments->Lines[0]->ID); // DELETE purchase/attachment?ID=…
```

## Custom prices and product suppliers

`$cin7->customPrices()` is `custom-prices`, the prices set for one customer. It has no GET: a
product's are in its `CustomPrices`, and a customer's in its `ProductPrices`. `post()` and `put()`
take a `CustomPricesData`, a list of `CustomPrices` (`ProductPriceData`: a `Price`, a product by
`ProductID` or `ProductSKU` and a customer by `CustomerID` or `CustomerName`), as well as an array;
`delete($productId, $customerId)` removes one. A POST or PUT answers `{Errors}` and a DELETE
`{Success}`, which `json()` reads.

`$cin7->productSuppliers()` is `product-suppliers`: `get($productId)->dto()` is a
`ProductSuppliersData`, its `ProductSuppliers` each a `ProductSupplierData` with its
`ProductSupplierOptions`. `post()` and `put()` take the same `ProductSuppliersData` as well as an array,
and `delete($productId, $supplierId)` removes one supplier. An empty `ProductSupplierOptions` on a
PUT deletes the options, as every empty collection does.

```php
$this->cin7->customPrices()->post(CustomPricesData::from([
    'CustomPrices' => [['CustomerID' => '201a76af-9da8-4cb0-ab85-5570f36edff6', 'ProductSKU' => 'Screws-SKU - 001', 'Price' => 1.1]],
]));

$suppliers = $this->cin7->productSuppliers()->get($productId)->dto()->ProductSuppliers; // list<ProductSupplierData>
```
## Reference books

`$cin7->reference()` holds the `reference/…` resources, apart from `ref()`, whose paths are
`ref/…`.

`$cin7->reference()->deals()` is `reference/deals`, the product deals, with no DELETE: `get()` and
`paginate()` filter by `id` and `search`, and `dto()` is a `list<ProductDealData>`. A deal applies
discount rules, in `DealDiscounts` (`ProductDealDiscountData`), to the customers in `DealCustomers` and
`DealCustomerTags`, or to a `CustomersGroup`; each discount names the brands, categories, tags and
products it covers. `post()` takes a `ProductDealPostData` (a `Name`) and `put()` a `ProductDealPutData`
(an `ID` and `Name`) as well as an array, and both answer the saved deal.

`$cin7->reference()->discount()` is `reference/discount`, the product discount rules, with no DELETE:
`get()` and `paginate()` filter by `id` and `search`, and `dto()` is a `list<ProductDiscountRuleData>`
with its `DiscountLines` (`DiscountLineData`). `post()` takes a `ProductDiscountRulesPostData`, a list of
`DiscountRules` (each a `Name`, `IsActive` and `Type`: `Simple`, `QuantityBased` or `FreeShipping`),
and `put()` a `ProductDiscountRulePutData`, one bare rule with its `ID` and `Name`, as well as an
array; both answer the saved rule, so `dto()` is a `ProductDiscountRuleData`. A line's
`DiscountType` is one of `DiscountAmount`, `DiscountPercent`, `MarkupAmount`, `MarkupPercent`,
`PriceOverride`, `FlatAmount` and `FreeShipping`, which needs `OrderExceeds`.

`$cin7->reference()->shipZones()` is `reference/shipZones`, the zones that set a customer's shipping
fees: `get()` and `paginate()` filter by `id` and `search`, and `dto()` is a `list<ShippingZoneData>`
with its `AppliesTo` (`ShipZoneAppliesToData`: a country, state or postcode range and its
`ShippingRate`) and `Conditions` (`ShipZoneConditionData`: a `Price` or `Weight` range and its
`ShippingCost`). `post()` takes a `ShippingZonePostData` (`Name`, `IsRestZone`, `PricesInclTax` and
`Negative`) and `put()` a `ShippingZonePutData` (`ZoneID` and `Name`) as well as an array, and both
answer the saved zone, so `dto()` is a `ShippingZoneData`. `delete($shipZoneId)` sends `ShipZoneID`,
and answers `{Success}`.

`$cin7->reference()->shipZonesEnabled()` reads and sets whether shipping zones are enabled: `get()`
and `put(['IsEnabled' => true])` both answer a `ShipZonesEnabledData`.

```php
$zone = $this->cin7->reference()->shipZones()->post(ShippingZonePostData::from([
    'Name' => 'Zone',
    'IsRestZone' => false,
    'PricesInclTax' => false,
    'Negative' => false,
    'AppliesTo' => [['Country2' => 'DZ', 'ShippingRate' => 7000]],
]))->dto(); // ShippingZoneData
```
## Advanced sale

`$cin7->advancedSale()` reads the advanced sale the way `advancedPurchase()` reads the advanced
purchase, but the reference gives it no endpoint: it serves a sale with several fulfilments,
invoices and credit notes through `sale`. So `advancedSale()` has no requests or response classes of
its own. `get()`, `put()` and `delete()` send `GetSale`, `PutSale` and `DeleteSale`, and `dto()` is a
`SaleData` whose `Type` says `Advanced Sale`. `post()` takes an `AdvancedSalePostData`, which sends
`SaleType: Advanced` for you, or an array, which names `SaleType` itself. `fulfilment()`,
`invoice()`, `creditNote()`, `payment()` and `manualJournal()` return the `sale/…` resources: the
DELETE of a fulfilment, an invoice and a credit note works on advanced sales only, and a POST to
`sale/fulfilment` on a simple sale turns it into an advanced one.

```php
use Ipsocode\Cin7\Data\AdvancedSale\AdvancedSalePostData;

$sale = $this->cin7->advancedSale()->post(AdvancedSalePostData::from([
    'Customer' => 'ACME',
    'Location' => 'Main Warehouse',
    'CurrencyRate' => 1,
]))->dto(); // SaleData

$this->cin7->advancedSale()->fulfilment()->delete($taskId); // DELETE sale/fulfilment?TaskID=…
```

## Advanced purchase

`$cin7->advancedPurchase()` is `advanced-purchase`, a purchase of any kind: simple, advanced or
service, where `purchase` supports only simple ones. It is keyed: `get($id)` sends
`advanced-purchase?ID=…`, and `combineAdditionalCharges: true` lists the additional charges in
`Lines` (a service purchase, which has only additional charges, answers with empty `Lines` without
it). `post()` takes an `AdvancedPurchasePostData` and `put()` an `AdvancedPurchasePutData` as well
as an array; a write needs `Location` and the supplier, by `Supplier` or `SupplierID`, a POST also
`Approach` (`INVOICE` or `STOCK`) and, optionally, `PurchaseType` (`Simple` or `Advanced`, a
`ProcessType`), which PUT does not take, and a PUT the purchase's `ID`. `delete($id, void: true)`
sends `advanced-purchase?ID=…&Void=true` and voids the purchase, and `void: false` undoes a void;
without `void` no `Void` is sent, and the reference defaults it to `false`. Every action answers
with the purchase, so `dto()` is an `AdvancedPurchaseData`, which carries its `Order` and the lists
of its `StockReceived`, `PutAway`, `Invoice`, `CreditNote` and `ManualJournals`. `advanced-purchase`
has no list action; list purchases through `purchaseList()`.

```php
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchasePostData;

$purchase = $this->cin7->advancedPurchase()->post(AdvancedPurchasePostData::from([
    'Supplier' => 'ABPA',
    'Approach' => 'STOCK',
    'Location' => 'Main Warehouse',
    'PurchaseType' => 'Advanced',
]))->dto(); // AdvancedPurchaseData

$invoices = $this->cin7->advancedPurchase()->get($purchase->ID)->dto()->Invoice; // list<AdvancedPurchaseInvoiceData>

$this->cin7->advancedPurchase()->delete($purchase->ID, void: true); // DELETE advanced-purchase?ID=…&Void=true
```

Its documents are sub-resources below it, as the paths are: `->stock()`, `->putAway()`,
`->invoice()`, `->creditNote()`, `->payment()` and `->manualJournal()` send
`advanced-purchase/stock`, `advanced-purchase/put-away`, `advanced-purchase/invoice`,
`advanced-purchase/creditnote`, `advanced-purchase/payment` and `advanced-purchase/manualJournal`.
Each is read by the purchase's `ID`, sent as `PurchaseID`.

`$cin7->advancedPurchase()->stock()` is `advanced-purchase/stock`, an advanced purchase's stock
received. It is not available for a service purchase, and only when `Use Put Away` is set in the
General Settings; a POST or PUT for a simple purchase converts it to an advanced one. Every method
answers with the purchase's stock receiving tasks, the `{PurchaseID, StockReceiving}` envelope, so
its `dto()` is an `AdvancedPurchaseStocksData`, whose `StockReceiving` are
`AdvancedPurchaseStockData`. `get($purchaseId)` sends `advanced-purchase/stock?PurchaseID=…`.
`post()` takes an `AdvancedPurchaseStockPostData` and `put()` an `AdvancedPurchaseStockPutData` as
well as an array; both need the `PurchaseID`, a `Status` of `DRAFT` or `AUTHORISED` and `Lines`,
and a PUT also the `TaskID` of the task it overwrites. A POST only adds lines: without a `TaskID`,
or with the empty GUID, it creates a new task, and with `Status` `AUTHORISED` and empty `Lines` it
authorises the task. Both fail unless the order is authorised, the stock received is `DRAFT`,
`NOT AVAILABLE` or `PARTIALLY RECEIVED`, and, for an `INVOICE` approach, the invoice is authorised.
`delete($taskId)` sends `advanced-purchase/stock?TaskID=…`; `void: true` voids the task, and
`void: false` undoes a void; without `void` no `Void` is sent, and the reference defaults it to
`false`. It is not available for a simple purchase.

```php
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStockPostData;

$received = $this->cin7->advancedPurchase()->stock()->get($purchaseId)->dto(); // AdvancedPurchaseStocksData

$saved = $this->cin7->advancedPurchase()->stock()->post(AdvancedPurchaseStockPostData::from([
    'PurchaseID' => $purchaseId,
    'Status' => 'AUTHORISED',
    'Lines' => [['Date' => '2018-04-23T00:00:00', 'Quantity' => 6, 'SKU' => 'Bread']],
]))->dto(); // AdvancedPurchaseStocksData
$taskId = $saved->StockReceiving[0]->TaskID;

$this->cin7->advancedPurchase()->stock()->delete($taskId, void: true); // DELETE advanced-purchase/stock?TaskID=…&Void=true
```

`$cin7->advancedPurchase()->putAway()` is `advanced-purchase/put-away`, an advanced purchase's put
away. Both methods answer with the purchase's put away tasks, the `{PurchaseID, PutAway}` envelope,
so their `dto()` is an `AdvancedPurchasePutAwaysData`, whose `PutAway` are
`AdvancedPurchasePutAwayData`. `get($purchaseId)` sends `advanced-purchase/put-away?PurchaseID=…`.
`post()` takes an `AdvancedPurchasePutAwayPostData` as well as an array; it needs the `PurchaseID`,
a `Status` of `DRAFT` or `AUTHORISED` (only `AUTHORISED` once the invoice lines match the
receiving) and `Lines`, each with its `Location` or `LocationID`. A POST only adds lines: without a
`TaskID`, or with the empty GUID, it creates a new task, and with `Status` `AUTHORISED` and empty
`Lines` it authorises the task. Duplicate lines in one body become one line with their quantities
summed, and a line with the product, location, batch and expiry date of an existing line fails.
It fails unless the order is authorised, the put away is `DRAFT` or `NOT AVAILABLE`, and, for an
`INVOICE` approach, the invoice is authorised. The put away has no PUT or DELETE.

```php
use Ipsocode\Cin7\Data\AdvancedPurchase\PutAway\AdvancedPurchasePutAwayPostData;

$putAway = $this->cin7->advancedPurchase()->putAway()->get($purchaseId)->dto(); // AdvancedPurchasePutAwaysData

$saved = $this->cin7->advancedPurchase()->putAway()->post(AdvancedPurchasePutAwayPostData::from([
    'PurchaseID' => $purchaseId,
    'Status' => 'AUTHORISED',
    'Lines' => [['Date' => '2018-04-20T00:00:00', 'Quantity' => 4, 'SKU' => 'Bread', 'Location' => 'Main Warehouse']],
]))->dto(); // AdvancedPurchasePutAwaysData
$taskId = $saved->PutAway[0]->TaskID;
```

`$cin7->advancedPurchase()->invoice()` is `advanced-purchase/invoice`, an advanced purchase's
invoices; a simple purchase's invoice is on `purchase/invoice`. Every method answers with the
purchase's invoices, the `{PurchaseID, Invoices}` envelope, so its `dto()` is an
`AdvancedPurchaseInvoicesData`, whose `Invoices` are `AdvancedPurchasePartialInvoiceData`.
`get($purchaseId)` sends `advanced-purchase/invoice?PurchaseID=…`, and
`combineAdditionalCharges: true` lists the additional charges in `Lines`. `post()` takes an
`AdvancedPurchasePartialInvoicePostData` as well as an array: one invoice task's fields with the
purchase's `PurchaseID` beside them. It needs the `PurchaseID`, the `TaskID`,
`CombineAdditionalCharges`, `InvoiceDate`, `InvoiceDueDate`, a `Status` of `DRAFT` or `AUTHORISED`
and `Lines`, but no totals; each line needs its `Account` and `Total`, and each additional charge
its `Account`. Cin7 rejects it unless the order is `AUTHORISED` and the invoice is `DRAFT` or
`NOT AVAILABLE`, and, for a purchase whose `Approach` is `STOCK`, the stock received is
`AUTHORISED`. `delete($taskId)` sends `advanced-purchase/invoice?TaskID=…`; `void: true` voids the
invoice, and `void: false` undoes a void; without `void` no `Void` is sent, and the reference
defaults it to `false`. It is not available for a simple purchase.

```php
use Ipsocode\Cin7\Data\AdvancedPurchase\Invoice\AdvancedPurchasePartialInvoicePostData;

$invoices = $this->cin7->advancedPurchase()->invoice()->get($purchaseId)->dto(); // AdvancedPurchaseInvoicesData

$this->cin7->advancedPurchase()->invoice()->post(AdvancedPurchasePartialInvoicePostData::from([
    ...$invoices->Invoices[0]->toArray(),
    'PurchaseID' => $purchaseId,
    'Status' => 'AUTHORISED',
]));

$this->cin7->advancedPurchase()->invoice()->delete($invoices->Invoices[0]->TaskID, void: true); // DELETE advanced-purchase/invoice?TaskID=…&Void=true
```

`$cin7->advancedPurchase()->creditNote()` is `advanced-purchase/creditnote`, an advanced purchase's
credit notes. Every method answers with the purchase's credit notes, the `{PurchaseID, CreditNotes}`
envelope, so its `dto()` is an `AdvancedPurchaseCreditNotesData`, whose `CreditNotes` are
`AdvancedPurchasePartialCreditNoteData`. `get($purchaseId)` sends
`advanced-purchase/creditnote?PurchaseID=…`, and `combineAdditionalCharges: true` lists the
additional charges in `Lines`. `post()` takes an `AdvancedPurchasePartialCreditNotePostData` as well
as an array: one credit note, which needs the `PurchaseID`, its `TaskID` (the empty GUID creates a
credit note), `CombineAdditionalCharges`, `CreditNoteNumber`, `CreditNoteInvoiceNumber`,
`CreditNoteDate`, a `Status` of `DRAFT` or `AUTHORISED`, `Lines` and `Unstock`, but no totals; each
line needs its `Account` and `Total`, each additional charge its `Account`, and each unstock line its
stock batch's `CardID` and `Quantity`. An unstock line's product, location, batch and expiry are
read-only and left out of the body. Cin7 rejects the POST unless the invoice is `AUTHORISED` or
`PAID` and the credit note is `DRAFT` or `NOT AVAILABLE`. `delete($taskId)` sends
`advanced-purchase/creditnote?TaskID=…` and voids that credit note; the reference documents no
`Void` flag for it.

```php
use Ipsocode\Cin7\Data\AdvancedPurchase\CreditNote\AdvancedPurchasePartialCreditNotePostData;

$creditNotes = $this->cin7->advancedPurchase()->creditNote()->get($purchaseId)->dto(); // AdvancedPurchaseCreditNotesData

$saved = $this->cin7->advancedPurchase()->creditNote()->post(AdvancedPurchasePartialCreditNotePostData::from([
    ...$creditNotes->CreditNotes[0]->toArray(),
    'PurchaseID' => $purchaseId,
    'Status' => 'AUTHORISED',
]))->dto(); // AdvancedPurchaseCreditNotesData

$this->cin7->advancedPurchase()->creditNote()->delete($saved->CreditNotes[0]->TaskID); // DELETE advanced-purchase/creditnote?TaskID=…
```

`$cin7->advancedPurchase()->payment()` is `advanced-purchase/payment`, an advanced purchase's
payments. `get()` takes the purchase's `purchaseId`, or the number of its order, invoice or credit
note (`orderNumber`, `invoiceNumber`, `creditNoteNumber`), all optional, and sends the ones given,
as in `advanced-purchase/payment?PurchaseID=…`; its `dto()` is a `list<AdvancedPurchasePaymentData>`,
read from a bare array. `post()` takes an `AdvancedPurchasePaymentPostData` and `put()` an
`AdvancedPurchasePaymentPutData` as well as an array; both answer with the saved payment, an
`AdvancedPurchasePaymentData`, which also carries the purchase's `PurchaseID`. The bodies are the
simple purchase's: a payment POST needs `TaskID`, `Type`, `Amount`, `DatePaid`, `Account` and
`CurrencyRate`, and takes a `DepositID` to pay from a supplier deposit; a payment PUT needs
`TaskID`, `ID`, `DatePaid` and `CurrencyRate`, and takes no `Amount` or `Account` for a payment
from a deposit. A payment needs an authorised invoice and a refund an authorised credit note, and
a prepayment cannot be changed. The reference documents the DELETE on `/purchase/payment`, so
`delete($id)` sends `DeletePurchasePayment`, `purchase/payment?ID=…`, and answers `{Success}`;
`deleteAllocation` works as on `$cin7->purchase()->payment()`.

```php
use Ipsocode\Cin7\Data\AdvancedPurchase\Payment\AdvancedPurchasePaymentPostData;

$payments = $this->cin7->advancedPurchase()->payment()->get($purchaseId)->dto(); // list<AdvancedPurchasePaymentData>
$invoicePayments = $this->cin7->advancedPurchase()->payment()->get(invoiceNumber: $invoiceNumber)->dto();

$saved = $this->cin7->advancedPurchase()->payment()->post(AdvancedPurchasePaymentPostData::from([
    'TaskID' => $taskId,
    'Type' => 'Payment',
    'Amount' => 9.0,
    'DatePaid' => '2017-12-21T00:00:00',
    'Account' => '718',
    'CurrencyRate' => 1.0,
]))->dto(); // AdvancedPurchasePaymentData

$this->cin7->advancedPurchase()->payment()->delete($saved->ID); // DELETE purchase/payment?ID=…
```

`$cin7->advancedPurchase()->manualJournal()` is `advanced-purchase/manualJournal`, an advanced
purchase's manual journals, each keyed by the `TaskID` of its purchase invoice task. Both methods
answer with the `{PurchaseID, ManualJournals}` envelope, so the `dto()` is an
`AdvancedPurchaseManualJournalsData`, whose `ManualJournals` are
`AdvancedPurchasePartialManualJournalData`. `get($purchaseId)` sends
`advanced-purchase/manualJournal?PurchaseID=…`. `post()` takes an
`AdvancedPurchasePartialManualJournalPostData` as well as an array; it needs the `PurchaseID`, the
journal's `TaskID` and a `Status` of `DRAFT` or `AUTHORISED`, and can be sent even when the journal
is authorised. A line's `IsSystem` is read-only and never sent: a line Cin7 posted (`IsSystem`
`true`) cannot be changed or deleted.

```php
use Ipsocode\Cin7\Data\AdvancedPurchase\ManualJournal\AdvancedPurchasePartialManualJournalPostData;

$journals = $this->cin7->advancedPurchase()->manualJournal()->post(AdvancedPurchasePartialManualJournalPostData::from([
    'PurchaseID' => $purchaseId,
    'TaskID' => $invoices->Invoices[0]->TaskID, // the invoice task's
    'Status' => 'DRAFT',
    'Lines' => [['Reference' => 'Freight', 'Amount' => 20.0, 'Date' => '2018-04-23T00:00:00', 'Debit' => '715', 'Credit' => '860']],
]))->dto(); // AdvancedPurchaseManualJournalsData
```

## PUT identifiers

A PUT body carries the identifier V2 documents for that resource. The caller
assigns it last, so it wins over anything already in the array under the same
key:

| Resource | PUT body carries |
|---|---|
| `customer` | `ID` |
| `supplier` | `ID` |
| `me/addresses` | `AddressID` |
| `me/contacts` | `ContactID` |
| `product` | `ID` |
| `ref/tax` | `ID` |
| `sale` | `ID` |
| `sale/invoice` | `SaleID` and `TaskID` |
| `sale/payment` | `ID` |
| `purchase` | `ID` |
| `purchase/payment` | `TaskID` and `ID` |
| `advanced-purchase` | `ID` |
| `advanced-purchase/stock` | `PurchaseID` and `TaskID` |
| `advanced-purchase/payment` | `TaskID` and `ID` |
| `moneyOperation` | `TaskID` |

```php
$attributes['ID'] = $guid;

$this->cin7->customer()->put($attributes);
```

## Adding an action

A new resource method is a thin wrapper, never a reimplementation of a
request:

1. Add the request class under `src/Requests/<Path>/`, extending `ListRequest`,
   `WriteRequest`, or `Cin7Request` for a read or delete of one record, with each documented
   query parameter as a typed constructor argument (see
   [writing a request class](requests.md#writing-a-request-class)).
2. Add the method to the resource, with the request's arguments, delegating to
   `$this->connector->send()` or `$this->connector->paginate()`.
3. Add its `requests` and `resources` rows to the path's file under `tests/Fixtures/Catalogue/`
   (see [the catalogue](testing.md#the-catalogue)).
4. Add the accessor, and its test in
   [`ConnectorResourcesTest`](../tests/Feature/Resources/ConnectorResourcesTest.php),
   only when it is new.
5. Document the resource here and the request in [requests](requests.md).

## Stock

`$cin7->stockAdjustmentList()` is `stockadjustmentList`, filtered by `status` (a `CompletionStatus`),
and its `get()->dto()` is a `list<StockAdjustmentListData>`.

`$cin7->stockAdjustment()` is `stockadjustment`, keyed by `TaskID` (`get($taskId)`), with no list
action of its own. `post()` takes a `StockAdjustmentPostData` and `put()` a `StockAdjustmentPutData`,
which requires `TaskID`, as well as an array. A body needs its `EffectiveDate`, `Status` (`DRAFT` or
`COMPLETED`) and `Lines`, each a `NewStockLineData`: a `Quantity` and `UnitCost`, its product by
`ProductID` or `SKU` and its location by `LocationID` or `Location`. `UpdateOnHand: true` adjusts the
quantity on hand and not the available quantity. Every action answers with the adjustment, so
`dto()` is a `StockAdjustmentData`, with the lines it changed (`ExistingStockLines`, in non-zero stock,
and `NewStockLines`, in zero stock) and the `Transactions` they created. `delete($id, void: true)`
voids it.

```php
$adjustment = $this->cin7->stockAdjustment()->post(StockAdjustmentPostData::from([
    'EffectiveDate' => '2017-12-01T00:00:00',
    'Status' => 'DRAFT',
    'Lines' => [['SKU' => 'AF308', 'Quantity' => 600, 'UnitCost' => 1, 'Location' => 'Main Warehouse']],
]))->dto(); // StockAdjustmentData
```

`$cin7->stockTakeList()` is `stockTakeList`, filtered by `status` (a `StockTakeStatus`: `DRAFT`,
`IN PROGRESS`, `COMPLETED`, `VOIDED`); its `dto()` is a `list<StockTakeListData>`, read from
`StockAdjustmentList`, the key the reference's example uses.

`$cin7->stockTake()` is `stocktake`, keyed by `TaskID`. `post()` takes a `StockTakePostData` and
`put()` a `StockTakePutData`, which requires `TaskID` and `Status`, as well as an array. A body
needs its `EffectiveDate`, `Account` and a location, by `LocationID` or `Location`. The filters
(`Tags`, `PickZones`, `StockLocators`, `Categories`, `Brands`, `Bins`) choose the products Cin7 puts
in `NonZeroStockOnHandProducts` on a POST, or on a PUT that moves `Status` from `DRAFT` to
`IN PROGRESS`; `ZeroStockOnHandProducts` are the `NewStockLineData` you add, and
`UseRelativeQuantity` says whether zero-stock products are included. Every action answers with the
stock take, so `dto()` is a `StockTakeData`. `delete($id, void: true)` voids it.

```php
$take = $this->cin7->stockTake()->post(StockTakePostData::from([
    'EffectiveDate' => '2018-04-27T00:00:00',
    'Account' => '403',
    'Location' => 'Main Warehouse',
    'Tags' => ['bread'],
]))->dto(); // StockTakeData
```

`$cin7->stockTransferList()` is `stockTransferList`, filtered by `status` (a `StockTransferStatus`:
`DRAFT`, `IN TRANSIT`, `COMPLETED`, `VOIDED`) and `search`.

`$cin7->stockTransfer()` is `stockTransfer`, keyed by `TaskID`. `post()` takes a
`StockTransferPostData` and `put()` a `StockTransferPutData`, which requires `TaskID`, as well as an
array. A body needs its `Status`, `CompletionDate` and `Lines` (`StockTransferLineData`: a
`TransferQuantity` and a product by `ProductID` or `SKU`), the location it moves stock from, by
`From` or `FromLocation`, and the one it moves it to, by `To` or `ToLocation`; an `IN TRANSIT`
transfer also needs its `InTransitAccount` and `DepartureDate`. `SkipOrder` skips the transfer
order, and `CostDistributionType` (`Cost`, `Quantity`, `Weight` or `Volume`) says how additional
journals are capitalised. Every action answers with the transfer, so `dto()` is a
`StockTransferData`, with its `Number`, its `Order` and `LastModifiedOn`. `delete($id, void: true)`
voids it.

`$cin7->stockTransfer()->order()` is `stockTransfer/order`: `get($taskId)` reads the order of a
transfer, and `post()` takes a `StockTransferOrderPostData` (`TaskID`, `Status` and `Lines`) as well
as an array. The order's `Status` is `NOT AVAILABLE`, `DRAFT` or `AUTHORISED`, and `dto()` is a
`StockTransferOrderData`.

```php
$transfer = $this->cin7->stockTransfer()->post(StockTransferPostData::from([
    'Status' => 'DRAFT',
    'CompletionDate' => '2017-12-19T00:00:00',
    'FromLocation' => 'Main Warehouse',
    'ToLocation' => 'Main Warehouse: Bin 1',
    'Lines' => [['SKU' => 'Bread', 'TransferQuantity' => 100]],
]))->dto(); // StockTransferData
```

## Inventory write-off

`$cin7->inventoryWriteOffList()` is `inventoryWriteOffList`, filtered by `status` (a
`CompletionStatus`) and `search`; its `dto()` is a `list<InventoryWriteOffListData>`.

`$cin7->inventoryWriteOff()` is `inventoryWriteOff`, keyed by `TaskID`. `post()` takes an
`InventoryWriteOffPostData` and `put()` an `InventoryWriteOffPutData`, which requires `TaskID`, as
well as an array. A body needs its `Status` (`DRAFT` or `COMPLETED`), its `Account`, a location by
`LocationID` or `Location`, and an `EffectiveDate` when it is `COMPLETED`; each line
(`InventoryWriteOffLineData`) needs its `Quantity` and a product by `ProductID` or `ProductCode`.
Every action answers with the write-off, so `dto()` is an `InventoryWriteOffData`, with its
`InventoryWriteOffNumber`, its `Transactions` and the `Errors` of a POST or PUT that created the task
despite them. `delete($id, void: true)` voids it.

```php
$writeOff = $this->cin7->inventoryWriteOff()->post(InventoryWriteOffPostData::from([
    'Status' => 'DRAFT',
    'Account' => '404',
    'Location' => 'Main Warehouse',
    'Lines' => [['ProductCode' => 'Bread', 'Quantity' => 2]],
]))->dto(); // InventoryWriteOffData
```
