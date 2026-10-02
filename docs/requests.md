# Requests

Every call to Cin7 is a request class in `Ipsocode\Cin7\Requests`, one per API
action, grouped under a [resource](resources.md) such as `CustomerResource`.
Every request extends [`Cin7Request`](../src/Requests/Cin7Request.php), which sets the bounded
retry policy for Cin7's throttling responses (429 and 503) and maps query values to the strings
Cin7 expects. Lists and write bodies have a base of their own; a read or delete of one record
extends `Cin7Request` itself.

## The bases

| Base | Verb | Constructor | Request line |
|---|---|---|---|
| [`ListRequest`](../src/Requests/ListRequest.php) | GET | `(?int $page = null, ?int $limit = null)`, then the list's filters | `GET <path>?<filters>&page=1&limit=100` |
| [`Cin7Request`](../src/Requests/Cin7Request.php) | GET or DELETE of one record | the identifier, then the other parameters | `<verb> <path>?<identifier key>=<id>&<parameters>` |
| [`WriteRequest`](../src/Requests/WriteRequest.php) | POST or PUT | `(array\|Data $body = [])` | `<verb> <path>`, an array body as JSON verbatim, a data object as described in [data](data.md#write-bodies) |

A subclass declares its verb as a property default and its path through `resolveEndpoint()`. A
`ListRequest` also declares the envelope key its items sit under (`protected string $listKey`)
and returns its filters by wire key from `filters()`. A read or delete of one record returns its
parameters by wire key from `defaultQuery()`, through `queryValues()`. Only `ListRequest`
implements `Paginatable`.

```php
use Ipsocode\Cin7\Cin7Connector;

public function __construct(private readonly Cin7Connector $cin7) {}

// GET customer?page=1&limit=100
$all = $this->cin7->customer()->get()->json();

// POST customer with body {"Name":"ACME"}
$new = $this->cin7->customer()->post(['Name' => 'ACME'])->json();

// PUT customer with body {"ID":"…","Name":"ACME Ltd"}
$this->cin7->customer()->put(['ID' => $guid, 'Name' => 'ACME Ltd']);
```

`json()` returns Cin7's response decoded to an associative array; `dto()` returns a typed
data object where the request has one ([data](data.md)), and a `WriteRequest` takes a data
object as its body as well as an array. Requests are
never constructed directly by application code; go through the
[resource](resources.md) accessor instead. Walking every page of a
`ListRequest` is covered in [pagination](pagination.md).

## Query parameters

Every query parameter the reference documents is a typed constructor argument, and the resource
method that sends the request takes the same arguments in the same order:

- **Names.** An argument is its wire key in camelCase: `CombineAdditionalCharges` is
  `combineAdditionalCharges`, `OrderLocationID` is `orderLocationId` and `IncludeBOM` is
  `includeBom`.
- **Types.** A GUID or text is a `string`, a Boolean a `bool`, a date a
  `DateTimeInterface|string`, and a documented value list its enum (see [data](data.md#conventions)).
- **Required first.** A required parameter, the identifier of a read or delete, comes first and
  has no default. The optional ones default to `null`, and a `null` one is not sent, so Cin7
  applies its own default: a `DELETE` without `void` is sent without `Void`, which the reference
  defaults to `false`.
- **Order.** Parameters are sent in the order the reference lists them, identifier first, and a
  list's filters come before `page` and `limit`.

```php
use Ipsocode\Cin7\Enums\SaleStatus;

// GET sale?ID=…&IncludeTransactions=true
$sale = $this->cin7->sale()->get($saleId, includeTransactions: true)->dto();

// GET saleList?Status=ORDERED&ReadyForShipping=true&page=1&limit=100
$orders = $this->cin7->saleList()->get(status: SaleStatus::Ordered, readyForShipping: true)->dto();

// DELETE sale?ID=…&Void=true
$this->cin7->sale()->delete($saleId, void: true);
```

The arguments of each request; a required one is in bold, and an enum's type follows its name:

| Request | Arguments |
|---|---|
| `GetCustomer` | `page`, `limit`, `id`, `name`, `modifiedSince`, `includeDeprecated`, `includeProductPrices`, `contactFilter` |
| `GetSupplier` | `page`, `limit`, `id`, `name`, `modifiedSince`, `includeDeprecated` |
| `GetProduct` | `page`, `limit`, `id`, `name`, `sku`, `modifiedSince`, `includeDeprecated`, `includeBom`, `includeSuppliers`, `includeMovements`, `includeAttachments`, `includeReorderLevels`, `includeCustomPrices` |
| `GetTax` | `page`, `limit`, `id`, `name`, `isActive`, `isTaxForSale`, `isTaxForPurchase`, `account` |
| `GetCustomerCredits` | `page`, `limit`, `customerId`, `showUsedCredits` |
| `GetSupplierDeposits` | `page`, `limit`, `supplierId`, `showUsedDeposits` |
| `GetAccount` | `page`, `limit`, `code`, `name`, `type`, `status` |
| `DeleteAccount` | **`code`** |
| `GetAccountBank` | `page`, `limit`, `id`, `name`, `bank` |
| `GetFixedAssetType` | `page`, `limit`, `fixedAssetTypeId`, `name` |
| `GetPaymentTerm` | `page`, `limit`, `id`, `name`, `termMethod` (`PaymentTermMethod`; `method` on the resource, since a request already has a `$method`), `isActive`, `isDefault` |
| `DeletePaymentTerm` | **`id`** |
| `GetMeAddresses` | `page`, `limit`, `id`, `type` (`AddressType`), `defaultForType`, `country`, `stateProvince`, `citySuburb` |
| `GetMeContacts` | `page`, `limit`, `id`, `name`, `type` (`ContactType`), `defaultForType`, `phone`, `fax`, `email` |
| `DeleteMeAddresses`, `DeleteMeContacts` | **`id`** |
| `GetBankTransfer` | **`taskId`** |
| `DeleteBankTransfer` | **`id`**, `void` |
| `GetJournal` | `page`, `limit`, `taskId`, `status` (`CompletionStatus`), `search` |
| `DeleteJournal` | **`id`**, `void` |
| `GetTransactions` | `page`, `limit`, `fromDate`, `toDate`, `account` |
| `GetMoneyTaskList` | `page`, `limit`, `status` (`CompletionStatus`), `search`, `taskType` (`MoneyTaskType`) |
| `GetMoneyTask` | **`taskId`** |
| `DeleteMoneyTask` | **`id`**, `void` |
| `GetSaleList` | `page`, `limit`, `search`, `createdSince`, `updatedSince`, `updatedUntil`, `shipBy`, `quoteStatus` (`TaskStatus`), `orderStatus` (`OrderStatus`), `combinedPickStatus` (`PickingStatus`), `combinedPackStatus` (`PackingStatus`), `combinedShippingStatus` (`ShippingStatus`), `combinedInvoiceStatus`, `creditNoteStatus` (`TaskStatus`), `externalId`, `status` (`SaleStatus`), `readyForShipping`, `orderLocationId` |
| `GetSale` | **`id`**, `combineAdditionalCharges`, `hideInventoryMovements`, `includeTransactions`, `countryFormat` (`CountryFormat`) |
| `DeleteSale` | **`id`**, `void` |
| `GetSaleOrder` | **`saleId`**, `combineAdditionalCharges`, `includeProductInfo` |
| `GetSaleFulfilment` | **`saleId`**, `includeProductInfo` |
| `DeleteSaleFulfilment` | **`taskId`**, `void` |
| `GetSaleFulfilmentPick`, `GetSaleFulfilmentPack` | **`taskId`**, `includeProductInfo` |
| `GetSaleFulfilmentShip` | **`taskId`** |
| `GetSaleInvoice` | **`saleId`**, `combineAdditionalCharges`, `includeProductInfo` |
| `DeleteSaleInvoice` | **`taskId`**, `void` |
| `GetSaleCreditNote` | **`saleId`**, `combineAdditionalCharges`, `includeProductInfo`, `includePaymentInfo` |
| `DeleteSaleCreditNote` | **`taskId`**, `void` |
| `GetSalePayment` | **`saleId`** |
| `DeleteSalePayment` | **`id`** |
| `GetSaleQuote` | **`saleId`**, `combineAdditionalCharges`, `includeProductInfo` |
| `GetSaleManualJournal`, `GetSaleAttachment` | **`saleId`** |
| `DeleteSaleAttachment` | **`id`** |
| `GetSaleCreditNoteList` | `page`, `limit`, `search`, `createdSince`, `updatedSince`, `updatedUntil`, `creditNoteStatus` (`TaskStatus`), `status` (`SaleStatus`) |

`CombinedInvoiceStatus` stays a string: the reference's list for it does not match the values its
examples return (see
[data](data.md#where-the-references-tables-and-examples-disagree)).

The `product` actions follow the same shape: `GetProduct` (a `ListRequest`
keyed `Products`), `PostProduct` and `PutProduct` (`WriteRequest`s; the PUT body
must carry `ID`, and `PutProduct` throws an `InvalidArgumentException` without one, while
`PostProduct` leaves `ID` out of its body because Cin7 ignores it on POST), all on `product`; their
data object bodies are `ProductPostData` and `ProductPutData`. The `customer` and `product` list
requests' `dto()` is a
`list<CustomerData>` or `list<ProductData>`, and their POST and PUT `dto()` is the saved
record (`CustomerList.0`, `Products.0`).

The `supplier` actions, under `src/Requests/Supplier/`, are the customer's in shape:
`GetSupplier` (a `ListRequest` keyed `SupplierList`), and `PostSupplier` and `PutSupplier`
(`WriteRequest`s, whose data object bodies are `SupplierPostData` and `SupplierPutData`; the PUT
body carries `ID`). `GetSupplier`'s `dto()` is a `list<SupplierData>`, and the POST and PUT
`dto()` is the saved supplier (`SupplierList.0`).

The `me` actions live under `src/Requests/Me/`: `GetMe`, a `Cin7Request` that takes no
parameters, on `me`, whose `dto()` is a `MeData`; and one folder per sub-path, where each path
has the same four classes:

| Folder | Classes | `dto()` |
|---|---|---|
| `Addresses/` | `GetMeAddresses` (a `ListRequest` keyed `MeAddressesList`), `PostMeAddresses`, `PutMeAddresses` (the PUT body carries `AddressID`), `DeleteMeAddresses` (`ID`) | `list<MeAddressData>` for the GET, `MeAddressData` for POST and PUT (`MeAddressesList.0`); none for the DELETE, whose `{Success}` is left to `json()` |
| `Contacts/` | `GetMeContacts` (a `ListRequest` keyed `MeContactsList`), `PostMeContacts`, `PutMeContacts` (the PUT body carries `ContactID`), `DeleteMeContacts` (`ID`) | `list<MeContactData>` for the GET, `MeContactData` for POST and PUT (`MeContactsList.0`); none for the DELETE |

Their data object bodies are `MeAddressPostData` and `MeAddressPutData`, and `MeContactPostData`
and `MeContactPutData`.

The `ref` actions live under `src/Requests/Ref/`: `GetTax` (a `ListRequest` keyed
`TaxRuleList`), `PostTax` and `PutTax` (`WriteRequest`s, whose data object bodies are
`TaxPostData` and `TaxPutData`; the PUT body carries `ID`), all
on `ref/tax`; `GetCustomerCredits` (a `ListRequest` keyed `CustomerCredits`) on
`ref/customer/credits`; `GetSupplierDeposits` (a `ListRequest` keyed `SupplierDeposits`) on
`ref/supplier/deposits`; and `GetAccount` (a `ListRequest` keyed `AccountsList`), `PostAccount`
and `PutAccount` (`WriteRequest`s, whose data object bodies are `AccountPostData` and
`AccountPutData`; the PUT body's `Code` names the account) and `DeleteAccount` (keyed `Code`), all
on `ref/account`, under `Account/`. `GetAccount`'s `dto()` is a `list<AccountData>`, the POST
and PUT `dto()` the saved account (`AccountsList.0`), and `DeleteAccount`'s `{Success}` is left
to `json()`. `GetAccountBank` (a `ListRequest` keyed `BankAccountsList`), on
`ref/account/bank` under `Account/Bank/`, answers a `list<BankAccountData>`.

`ref/fixedassettype` (under `FixedAssetType/`) has `GetFixedAssetType` (a `ListRequest` keyed
`FixedAssetTypeList`), `PostFixedAssetType` and `PutFixedAssetType` (the PUT body carries
`FixedAssetTypeID`); `ref/paymentterm` (under `PaymentTerm/`) has `GetPaymentTerm` (keyed
`PaymentTermList`), `PostPaymentTerm`, `PutPaymentTerm` (the PUT body carries `ID`) and
`DeletePaymentTerm` (`ID`). Their GET `dto()` is a list of `FixedAssetTypeData` or
`PaymentTermData`, and POST and PUT answer the saved record (`<list key>.0`).

The `bankTransfer` actions live under `src/Requests/BankTransfer/`, and follow the Money Task's:
`GetBankTransfer` (keyed `TaskID`), `DeleteBankTransfer` (keyed `ID`, with `Void`), and
`PostBankTransfer` and `PutBankTransfer` (`WriteRequest`s, whose data object bodies are
`BankTransferPostData` and `BankTransferPutData`; the PUT body carries `TaskID`). Every one's
`dto()` is a `BankTransferData`.

The `journal` actions live under `src/Requests/Journal/`: `GetJournal` (a `ListRequest` keyed
`Journals`), `PostJournal` and `PutJournal` (`WriteRequest`s, whose data object bodies are
`JournalPostData` and `JournalPutData`; the PUT body carries `TaskID`) and `DeleteJournal` (keyed
`ID`, with `Void`). Every one's `dto()` is a `JournalData`, the first entry of `Journals`, and
`GetJournal`'s a `list<JournalData>`. `GetTransactions` (a `ListRequest` keyed `Transactions`) is
on `transactions`, under `src/Requests/Transactions/`; its `dto()` is a `list<TransactionData>`.

The `moneyOperation` actions live under `src/Requests/MoneyTask/`, named after the Money Task
model they serve: `GetMoneyTask`
(keyed `TaskID`), `DeleteMoneyTask` (keyed `ID`, with `Void`), and `PostMoneyTask` and
`PutMoneyTask` (`WriteRequest`s, whose data object bodies are `MoneyTaskPostData` and
`MoneyTaskPutData`; the PUT body carries `TaskID`), all on `moneyOperation`. Every one's `dto()` is
a `MoneyTaskData`.

`GetMoneyTaskList` (a `ListRequest` keyed `MoneyTasks`) is on `moneyTaskList`, under
`src/Requests/MoneyTaskList/`; its `dto()` is a `list<MoneyTaskListData>`.

The `sale` actions live under `src/Requests/Sale/`: `GetSale` and `DeleteSale` (keyed `ID`; the
DELETE takes `Void`) and `PostSale` and `PutSale`
(`WriteRequest`s; the PUT body carries `ID`, and `PutSale` leaves the POST-only `SaleType` out of it), all on `sale`. `sale` has no list action:
`GetSaleList` (a `ListRequest` keyed `SaleList`) is on `saleList`, under `src/Requests/SaleList/`,
and `GetSaleCreditNoteList` (keyed `SaleList` too) on `saleCreditNoteList`, under
`src/Requests/SaleCreditNoteList/`. Every `sale` request's `dto()` is a `SaleData`;
`GetSaleList`'s is a `list<SaleListData>` and `GetSaleCreditNoteList`'s a
`list<SaleCreditNoteListData>`.

The `sale/…` documents live under `src/Requests/Sale/`, one folder per path, 32 classes in all:

| Folder | Classes (identifier key, or `WriteRequest`) | `dto()` |
|---|---|---|
| `Quote/` | `GetSaleQuote` (`SaleID`), `PostSaleQuote` | `SaleQuoteData` |
| `Order/` | `GetSaleOrder` (`SaleID`), `PostSaleOrder` | `SaleOrderData` |
| `Fulfilment/` | `GetSaleFulfilment` (`SaleID`), `PostSaleFulfilment`, `DeleteSaleFulfilment` (`TaskID`) | `SaleFulfilmentsData` |
| `Fulfilment/Pick/` | `GetSaleFulfilmentPick` (`TaskID`), `PostSaleFulfilmentPick`, `PutSaleFulfilmentPick` | `SaleFulfilmentPickData` |
| `Fulfilment/Pack/` | `GetSaleFulfilmentPack` (`TaskID`), `PostSaleFulfilmentPack`, `PutSaleFulfilmentPack` | `SaleFulfilmentPackData` |
| `Fulfilment/Ship/` | `GetSaleFulfilmentShip` (`TaskID`), `PostSaleFulfilmentShip`, `PutSaleFulfilmentShip` | `SaleFulfilmentShipData` |
| `Invoice/` | `GetSaleInvoice` (`SaleID`), `PostSaleInvoice`, `PutSaleInvoice`, `DeleteSaleInvoice` (`TaskID`) | `SaleInvoicesData` |
| `CreditNote/` | `GetSaleCreditNote` (`SaleID`), `PostSaleCreditNote`, `DeleteSaleCreditNote` (`TaskID`) | `SaleCreditNotesData` |
| `Payment/` | `GetSalePayment` (`SaleID`), `PostSalePayment`, `PutSalePayment`, `DeleteSalePayment` (`ID`) | `list<SalePaymentLinePartialData>` for the GET, `SalePaymentLinePartialData` for POST and PUT; none for the DELETE |
| `ManualJournal/` | `GetSaleManualJournal` (`SaleID`), `PostSaleManualJournal` | `SaleManualJournalData` |
| `Attachment/` | `GetSaleAttachment` (`SaleID`), `PostSaleAttachment`, `DeleteSaleAttachment` (`ID`) | `SaleAttachmentsData` |

The write bodies are per verb where the reference's fields differ: `SaleInvoicePostData` and
`SaleInvoicePutData` for `sale/invoice`, `SaleCreditNotePostData` for `sale/creditnote`,
`SalePaymentPostData` and `SalePaymentPutData` for `sale/payment`, and a POST and a PUT class for
the fulfilment's pick, pack and ship. Each makes the fields the reference requires for that verb
mandatory; see [data](data.md).

## Wire protocol

These are the requests Cin7 receives.

- **Base URL.** `https://inventory.dearsystems.com/ExternalApi/v2/`, with the
  endpoint path appended relative to it, e.g.
  `https://inventory.dearsystems.com/ExternalApi/v2/customer`.
- **Headers.** Every request carries `Content-Type: application/json`,
  `api-auth-accountid` and `api-auth-applicationkey`, the last two from
  [configuration](configuration.md).
- **Parameters.** GET and DELETE carry their parameters in the query string.
  POST and PUT carry theirs as a raw JSON body and send no query string.
- **Page defaults.** `page=1` and `limit=100` are added to the
  query string of every `ListRequest` when the caller has not set them. They
  are never added to a read or delete of one record, or a `WriteRequest`. A page below 1 or a
  limit outside 1 to 1000 throws before anything is sent; see
  [page defaults](#page-defaults).
- **Unset parameters.** A `null` argument is left out of the query string, through
  `Cin7Request::queryValues()`.
- **Boolean query values.** A `true`/`false` argument goes out as the
  string `'true'`/`'false'`, not PHP's `1`/empty string, through the same method.
- **Enum query values.** An enum argument goes out as its value: `SaleStatus::Ordered` as
  `ORDERED`.
- **Date query values.** A `DateTimeInterface` argument goes out in
  the reference's date format, ISO 8601 converted to UTC with milliseconds
  (`yyyy-MM-ddTHH:mm:ss.fff`, e.g. `2012-11-14T13:28:33.363`), through the same
  method. A date string is sent as given.
- **Identifier placement.** A read or delete of one record sends its identifier first in the
  query string, under the key the reference documents for it (`ID`, `SaleID` or `TaskID`). A
  `WriteRequest` sends no identifier of its own; the caller merges it into the body, as in
  [PUT identifiers](resources.md#put-identifiers).
- **Empty write.** `new PostCustomer()` with no body still sends a JSON body,
  the encoding of an empty array (`[]`), not a bodyless POST.

## Fields left out of write bodies

Cin7's reference marks some fields read-only, response-only, or available for one method only. A
`WriteRequest` subclass lists those in `$omit`, and they never reach the body, whether the caller
passed an array or a data object. A path is dot-separated and `*` stands for every list item. A
data object's body is also stripped of nulls and validated after the omission; see
[write bodies](data.md#write-bodies).

| Request | Left out |
| --- | --- |
| `PostCustomer`, `PutCustomer` | `LastModifiedOn`, `ChildCustomers`, `ProductPrices.*.ProductName` |
| `PostSupplier`, `PutSupplier` | `LastModifiedOn` (see [data](data.md#where-the-references-tables-and-examples-disagree)) |
| `PostProduct` | `ID`, `AverageCost`, `LastModifiedOn`, `BOMType`, `Suppliers.*.Currency`, `BillOfMaterialsProducts.*.Name`, `CustomPrices.*.ProductName` |
| `PutProduct` | the same, with `Type` (read-only for PUT) in place of `ID`; `PutProduct` also needs an `ID` |
| `PostTax`, `PutTax` | `TaxPercent` |
| `PutSale` | `SaleType` (POST only) |
| `PostSaleOrder` | `Lines.*.BackorderQuantity` |
| `PostSalePayment` | `ID`, `CreditID` (PUT only) |
| `PutSalePayment` | `TaskID`, `Type` (POST only) |

## Page defaults

Cin7 expects `page` and `limit` on every list read, so `ListRequest` always
sends them. [`PageDefaults::apply()`](../src/PageDefaults.php):

- Sets `page` to `PageDefaults::PAGE` (`1`) and `limit` to
  `PageDefaults::LIMIT` (`100`) only when the caller has not. Caller values
  win.
- Sends lowercase keys, the spelling the paginator reads the sent limit from.
- Treats a `null` value as absent, so the default is used.
- Enforces Cin7's bounds: `page` must be a whole number of at least 1, and
  `limit` one from 1 to `PageDefaults::LIMIT_MAX` (1000), the largest page Cin7
  serves. Anything else throws an `InvalidArgumentException` from `send()`
  before the request goes out. The bound matters beyond Cin7 rejecting the
  call: the paginator counts pages by the limit it sent, so a limit Cin7 cut
  short would end the walk early. `Cin7Paginator` applies the same checks to
  `perPageLimit()` and `startPage()`.
- Appends the defaults after the list's filters, which keep their order.
- Exposes `PAGE` and `LIMIT` as public constants, so code paging by hand can
  read them instead of hard-coding `1` and `100`.
- Never mutates the caller's array.

## Errors

Every request uses Saloon's `AlwaysThrowOnErrors`, so a 4xx or 5xx response
throws once any retries are spent:

| Exception | When | Extends | Carries |
|---|---|---|---|
| `Hypervel\Saloon\Exceptions\Request\ClientException` | 4xx, e.g. `403 Incorrect credentials!` for a bad account ID or key, including a 429 that outlasted the retries | `Hypervel\Saloon\Exceptions\Request\RequestException` | The full `Response`: `response()`, `status()`, `body()`, `pendingRequest()` |
| `Hypervel\Saloon\Exceptions\Request\ServerException` | 5xx, including a 503 that outlasted the retries | `Hypervel\Saloon\Exceptions\Request\RequestException` | The full `Response`, as above |
| `Hypervel\Saloon\Exceptions\Request\RequestException` | A 2xx whose body is Cin7's Error Model (below) | `Hypervel\Http\Client\RequestException` | The full `Response`, as above |
| `Hypervel\Saloon\Exceptions\Request\FatalRequestException` | Transport failure: DNS, refused connection, timeout | `Hypervel\Saloon\Exceptions\SaloonException` | No response; `pendingRequest()` and the original exception as `getPrevious()` |

Saloon's `RequestException` extends `Hypervel\Http\Client\RequestException`,
not `SaloonException`, so a single `catch (SaloonException)` covers only the
transport failure, not a 4xx or 5xx response.

Cin7 reports a failure with its Error Model, `{"ErrorCode": 400, "Exception": "…"}`, or a
list starting with one, and sometimes with a 200. The connector fails any response whose body
carries `ErrorCode`, so it throws like a 4xx or 5xx: a 4xx or 5xx keeps its `ClientException` or
`ServerException`, and a 2xx throws a plain `RequestException` whose `status()` is the 2xx. Read
the error with `Ipsocode\Cin7\Data\Other\ErrorData`:

```php
use Hypervel\Saloon\Exceptions\Request\RequestException;
use Ipsocode\Cin7\Data\Other\ErrorData;

try {
    $this->cin7->sale()->payment()->get($saleId);
} catch (RequestException $exception) {
    $error = ErrorData::from($exception->response()->json()); // ->ErrorCode, ->Exception
}
```

A data object body that breaks its class's rules throws a `Hypervel\Validation\ValidationException`
from `send()` before anything goes out; see [write bodies](data.md#write-bodies). A page or limit
out of bounds throws an `InvalidArgumentException` the same way.

A request body that cannot be encoded as JSON throws from `send()` before
anything goes out: Saloon throws a `BodyException` when `json_encode()` fails,
and an `InvalidArgumentException` for a value that does not resolve to arrays,
scalars and `null`.

## Retry policy

Cin7 throttles with a 429, the status its reference documents for the limit of 60
calls a minute, or a 503. Those are the only responses retried:

- **429 and 503 only.** Any other 4xx or 5xx, 500 included, throws on the first
  attempt, as does an Error Model in a 200. A `FatalRequestException` (DNS, refused connection, timeout) is not
  retried either. A bad credential comes back as `403 Incorrect credentials!`
  on every call, so retrying it would only turn a configuration mistake into a
  delay of about 15 s (three 5 s waits) per call.
- **Bounded.** `cin7.retry.times` is the total number of attempts, not the
  number of extra ones, and `cin7.retry.delay_ms` is the wait between them. The
  defaults, `4` and `5000`, mean at most four attempts with three 5 s waits.
  When the attempts run out, the last 429 throws as a `ClientException` and the
  last 503 as a `ServerException`.
- **Clamped.** `times` is raised to at least `1` and `delay_ms` to at least `0`.
  Saloon's `RetryPolicy` throws an `InvalidArgumentException` for zero attempts
  or a negative delay, so without the clamp a bad environment value would fail
  every request at construction. At `0` ms the attempts run back to back.
- **Fallbacks.** A `null` or absent `retry.times` or `retry.delay_ms` falls back
  to `4` or `5000`, not to `0`.
- **Read at construction.** `Cin7Request`'s constructor reads the config and
  sets the policy on the request, because Saloon's `PendingRequest` copies the
  request's retry policy when it is built, before any boot hook runs. A config
  change therefore applies to requests constructed after it:

  ```php
  config(['cin7.retry.times' => 2, 'cin7.retry.delay_ms' => 250]);

  $request = new Ipsocode\Cin7\Requests\Customer\GetCustomer(); // reads the values above
  ```

The wait goes through `Hypervel\Support\Sleep`, which suspends only the
calling coroutine. A 429 or 503 off the wire also puts the API application into
the connector's cooldown, for a 429's `Retry-After` or 5 seconds, and the next
attempt waits it out; see [connector](connector.md#the-throttling-cooldown).

## Writing a request class

A new request extends the base matching its shape, declares its verb as a
property default, and implements `resolveEndpoint()`:

```php
use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\WriteRequest;

final class PutProduct extends WriteRequest
{
    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return 'product';
    }
}
```

A read or delete of one record extends `Cin7Request` itself. Its constructor takes the
identifier, then each documented parameter as a typed argument, and `defaultQuery()` sends them
by wire key through `queryValues()`:

```php
use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Requests\Cin7Request;

final class GetSaleOrder extends Cin7Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected readonly string $saleId,
        protected readonly ?bool $combineAdditionalCharges = null,
        protected readonly ?bool $includeProductInfo = null,
    ) {
        parent::__construct();
    }

    public function resolveEndpoint(): string
    {
        return 'sale/order';
    }

    protected function defaultQuery(): array
    {
        return $this->queryValues([
            'SaleID' => $this->saleId,
            'CombineAdditionalCharges' => $this->combineAdditionalCharges,
            'IncludeProductInfo' => $this->includeProductInfo,
        ]);
    }
}
```

A list request takes `?int $page = null, ?int $limit = null` first and passes them to
`parent::__construct($page, $limit)`, then returns its filters by wire key from `filters()`;
`ListRequest` maps them and adds the page defaults.

`Cin7Request`'s constructor reads `cin7.retry.*`, so a subclass that adds
constructor parameters must call `parent::__construct()`. PHP initializes
property defaults before any constructor body runs, so `$this->method()`
is always the declared verb; a subclass must never assign `$this->method` in
its own constructor.
