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
- **Methods are HTTP verbs.** `get()`, `post()`, `put()`, `delete()`, plus
  `paginate()` on list endpoints. A keyed `get()` or `delete()` takes the
  identifier as its first argument, e.g. `get(string $id, array $parameters =
  [])`.
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
| `$cin7->customer()` | `CustomerResource` | `get(array $filters = [])`, `paginate(array $filters = []): Cin7Paginator`, `post(array $body)`, `put(array $body)` |
| `$cin7->product()` | `ProductResource` | `get(array $filters = [])`, `paginate(array $filters = []): Cin7Paginator`, `post(array $body)`, `put(array $body)` |
| `$cin7->moneyOperation()` | `MoneyOperationResource` | `get(string $taskId)`, `post(array|MoneyTaskData $body)`, `put(array|MoneyTaskData $body)`, `delete(string $id, bool $void = false)` |
| `$cin7->sale()` | `SaleResource` | `get(string $id, array $parameters = [])`, `post(array|SalePostPutData $body)`, `put(array|SalePostPutData $body)`, `delete(string $id, bool $void = false)` |
| `$cin7->saleList()` | `SaleListResource` | `get(array $filters = [])`, `paginate(array $filters = []): Cin7Paginator` |
| `$cin7->ref()` | `RefResource` | `tax()`, `customer()`; a pure grouping, as V2 has no action on `/ref` |
| `$cin7->ref()->tax()` | `Ref\TaxResource` | `get(array $filters = [])`, `paginate(array $filters = []): Cin7Paginator`, `post(array|TaxData $body)`, `put(array|TaxData $body)` |
| `$cin7->ref()->customer()` | `Ref\CustomerResource` | `credits()`; also a pure grouping |
| `$cin7->ref()->customer()->credits()` | `Ref\Customer\CreditsResource` | `get(array $filters = [])`, `paginate(array $filters = []): Cin7Paginator` |

## Customer

`customer` has no GUID-keyed find: a filter such as `['ID' => $guid]` goes
through `get()` like any other filter, because V2 answers `customer?ID=…` with
the same `{Total, Page, CustomerList}` envelope as an unfiltered list.

```php
$match = $this->cin7->customer()->get(['ID' => $guid])->json('CustomerList')[0] ?? null;
```

## Product

`product` lists under `Products`, not `ProductList`: its envelope is
`{Total, Page, Products}`, and `GetProduct` declares that key, so
`$cin7->product()->paginate()` yields every product. The V2 list filters
(`Name`, `Sku`, `ModifiedSince`, `IncludeDeprecated`, `IncludeBOM`, …) go
through `get()` and `paginate()` as ordinary filters; booleans go out as
`true`/`false`.

```php
foreach ($this->cin7->product()->paginate(['IncludeDeprecated' => false])->items() as $product) {
    // $product is one entry of Products
}
```

## Ref

The reference data lives under `ref/…`, so the chain spells the path:
`$cin7->ref()->tax()` and `$cin7->ref()->customer()->credits()`.

`ref/tax` lists under `TaxRuleList` (`{Total, Page, TaxRuleList}`). Its data classes are
`TaxData` and `TaxComponentData`; `get()->dto()` is a `list<TaxData>` and `post()` and
`put()` accept a `TaxData` and return it from `dto()` (see [data](data.md)). Its V2 filters
(`ID`, `Name`, `IsActive`, `IsTaxForSale`, `IsTaxForPurchase`, `Account`) go through
`get()` and `paginate()` as ordinary filters.

`ref/customer/credits` lists under `CustomerCredits` (`get()->dto()` is a
`list<CustomerCreditData>`) and its envelope has no `Total`;
see [pagination](pagination.md#an-envelope-with-no-total). `CustomerID` and
`ShowUsedCredits` are ordinary filters.

```php
$vat = $this->cin7->ref()->tax()->get(['IsActive' => true])->json('TaxRuleList');

foreach ($this->cin7->ref()->customer()->credits()->paginate(['CustomerID' => $guid])->items() as $credit) {
    // $credit is one entry of CustomerCredits
}
```

## Money Operation

`moneyOperation` is keyed by `TaskID`: `get($taskId)` sends `moneyOperation?TaskID=…`. V2 marks
`TaskID` optional on GET, but the package requires it, because the list lives at
`moneyTaskList`, which is out of scope. `delete($id, $void)` sends
`moneyOperation?ID=…&Void=…`: `void: true` voids the task, and the default `false` undoes a
void. Every action answers with the Money Task, so `dto()` is a `MoneyTaskData` for `get()`,
`post()`, `put()` and `delete()`, with `Lines` (`MoneyTaskLineData`), `Transactions`
(`TransactionStockLineData`) and `Attachments` (`AttachmentLineData`). `post()` and `put()`
accept a `MoneyTaskData` as well as an array (see [data](data.md)); a PUT body carries
`TaskID`.

```php
$task = $this->cin7->moneyOperation()->get($taskId)->dto(); // MoneyTaskData

$this->cin7->moneyOperation()->post(MoneyTaskData::from([
    'TaskType' => 'Receive Money',
    'Status' => 'DRAFT',
    'BankAccount' => '198489',
    'Date' => '2018-01-17T00:00:00',
]));
$this->cin7->moneyOperation()->delete($taskId, void: true);
```

## Sale

`sale` is keyed: `get($id)` sends `sale?ID=…`, and the optional V2 parameters
(`CombineAdditionalCharges`, `HideInventoryMovements`, `IncludeTransactions`,
`CountryFormat`) go in the second argument. `sale` has no list action, so a `GetSale`
cannot be paginated; list sales through `saleList()`, whose envelope is
`{Total, Page, SaleList}` and whose filters (`Search`, `CreatedSince`, `UpdatedSince`,
`ShipBy`, the status filters, `ExternalID`, `ReadyForShipping`, `OrderLocationID`) go through
`get()` and `paginate()` as ordinary filters.

`delete($id, $void)` sends `sale?ID=…&Void=…`: `void: true` voids the sale, and the default
`false` undoes a void. Every `sale` action answers with the Sale, so `dto()` is a `SaleData`
for `get()`, `post()`, `put()` and `delete()`, and a `list<SaleListData>` for
`saleList()->get()`. `post()` and `put()` accept a `SalePostPutData` as well as an array (see
[data](data.md)).

```php
$sale = $this->cin7->sale()->get($guid, ['IncludeTransactions' => true])->dto(); // SaleData

foreach ($this->cin7->saleList()->paginate(['Status' => 'ORDERED'])->items() as $row) {
    // $row is one entry of SaleList
}

$this->cin7->sale()->delete($guid, void: true);
```

### Sale order, invoice, credit note and payment

`sale` also has four sub-resources, mirroring the V2 paths: `$cin7->sale()->order()`,
`->invoice()`, `->creditNote()` and `->payment()`. Their `get()` takes the sale's GUID
(`SaleID`) and optional parameters; they send `sale/order`, `sale/invoice`, `sale/creditnote`
and `sale/payment`.

| Resource | Methods | Body and `dto()` |
|---|---|---|
| `sale()->order()` (Sale Order Model) | `get(string $saleId, array $parameters = [])` (`CombineAdditionalCharges`, `IncludeProductInfo`), `post(array\|SaleOrderData $body)` | `SaleOrderData` |
| `sale()->invoice()` (Sale Invoice Partial and POST Models) | `get($saleId, …)`, `post(array\|SaleInvoicePostData)`, `put(array\|SaleInvoicePostData)`, `delete(string $taskId, bool $void = false)` | `SaleInvoicesData`, the `{SaleID, Invoices}` envelope |
| `sale()->creditNote()` (Sale Credit Note Partial and POST Models) | `get($saleId, …)` (also `IncludePaymentInfo`), `post(array\|SaleCreditNotePostData)`, `delete(string $taskId, bool $void = false)` | `SaleCreditNotesData`, the `{SaleID, CreditNotes}` envelope |
| `sale()->payment()` (Sale Payment Line Partial Model) | `get(string $saleId)`, `post(array\|SalePaymentLinePartialData)`, `put(array\|SalePaymentLinePartialData)`, `delete(string $id)` | `GET`: `list<SalePaymentLinePartialData>`; POST, PUT: one line; DELETE answers `{Success}` |

Invoice and credit note deletes go by `TaskID` and take `Void`; a payment delete goes by `ID`
and has no `Void`. An invoice POST needs `SaleID` and an empty-GUID `TaskID`.

```php
$this->cin7->sale()->invoice()->delete($taskId, void: true); // DELETE sale/invoice?TaskID=…&Void=true
$payments = $this->cin7->sale()->payment()->get($saleId)->dto(); // list<SalePaymentLinePartialData>
```

## PUT identifiers

A PUT body carries the identifier V2 documents for that resource. The caller
assigns it last, so it wins over anything already in the array under the same
key:

| Resource | PUT body carries |
|---|---|
| `customer` | `ID` |
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

1. Add the request class under `src/Requests/<Path>/`, extending
   `ListRequest`, `KeyedRequest` or `WriteRequest` as the action calls for (see
   [writing a request class](requests.md#writing-a-request-class)).
2. Add the method to the resource, delegating to `$this->connector->send()` or
   `$this->connector->paginate()`.
3. Add a row to
   [`RequestCatalogueTest`](../tests/Feature/Requests/RequestCatalogueTest.php)
   and to
   [`ResourceCatalogueTest`](../tests/Feature/Resources/ResourceCatalogueTest.php).
4. Add the accessor, and its test in
   [`ConnectorResourcesTest`](../tests/Feature/Resources/ConnectorResourcesTest.php),
   only when it is new.
5. Document the resource here and the request in [requests](requests.md).
