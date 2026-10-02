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
| `$cin7->product()` | `ProductResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|ProductPostData $body)`, `put(array\|ProductPutData $body)` |
| `$cin7->bankTransfer()` | `BankTransferResource` | `get(string $taskId)`, `post(array\|BankTransferPostData $body)`, `put(array\|BankTransferPutData $body)`, `delete(string $id, ?bool $void = null)` |
| `$cin7->journal()` | `JournalResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|JournalPostData $body)`, `put(array\|JournalPutData $body)`, `delete(string $id, ?bool $void = null)` |
| `$cin7->transactions()` | `TransactionsResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->moneyTask()` | `MoneyTaskResource` | `get(string $taskId)`, `post(array\|MoneyTaskPostData $body)`, `put(array\|MoneyTaskPutData $body)`, `delete(string $id, ?bool $void = null)` |
| `$cin7->sale()` | `SaleResource` | `get(string $id, …)`, `post(array\|SalePostData $body)`, `put(array\|SalePutData $body)`, `delete(string $id, ?bool $void = null)`; `quote()`, `order()`, `fulfilment()`, `invoice()`, `creditNote()`, `payment()`, `manualJournal()`, `attachment()` |
| `$cin7->sale()->fulfilment()` | `Sale\FulfilmentResource` | `get(string $saleId, …)`, `post(array\|SaleFulfilmentsData $body)`, `delete(string $taskId, ?bool $void = null)`; `pick()`, `pack()`, `ship()` |
| `$cin7->sale()->fulfilment()->pick()` | `Sale\Fulfilment\PickResource` | `get(string $taskId, …)`, `post(array\|SaleFulfilmentPickPostData $body)`, `put(array\|SaleFulfilmentPickPutData $body)` |
| `$cin7->sale()->fulfilment()->pack()` | `Sale\Fulfilment\PackResource` | `get(string $taskId, …)`, `post(array\|SaleFulfilmentPackPostData $body)`, `put(array\|SaleFulfilmentPackData $body)` |
| `$cin7->sale()->fulfilment()->ship()` | `Sale\Fulfilment\ShipResource` | `get(string $taskId)`, `post(array\|SaleFulfilmentShipPostData $body)`, `put(array\|SaleFulfilmentShipPutData $body)` |
| `$cin7->moneyTaskList()` | `MoneyTaskListResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->saleList()` | `SaleListResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->saleCreditNoteList()` | `SaleCreditNoteListResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->ref()` | `RefResource` | `tax()`, `customer()`, `supplier()`, `account()`, `fixedAssetType()`, `paymentTerm()`; a pure grouping, as V2 has no action on `/ref` |
| `$cin7->ref()->tax()` | `Ref\TaxResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|TaxPostData $body)`, `put(array\|TaxPutData $body)` |
| `$cin7->ref()->customer()` | `Ref\CustomerResource` | `credits()`; also a pure grouping |
| `$cin7->ref()->customer()->credits()` | `Ref\Customer\CreditsResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->ref()->supplier()` | `Ref\SupplierResource` | `deposits()`; also a pure grouping |
| `$cin7->ref()->supplier()->deposits()` | `Ref\Supplier\DepositsResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
| `$cin7->ref()->account()` | `Ref\AccountResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator`, `post(array\|AccountPostData $body)`, `put(array\|AccountPutData $body)`, `delete(string $code)`; `bank()` |
| `$cin7->ref()->account()->bank()` | `Ref\Account\BankResource` | `get($page, $limit, …)`, `paginate($limit, …): Cin7Paginator` |
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
