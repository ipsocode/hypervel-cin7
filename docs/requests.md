# Requests

Every call to Cin7 is a request class in `Ipsocode\Cin7\Requests`, one per API
action, grouped under a [resource](resources.md) such as `CustomerResource`.
Three bases cover the shapes Cin7's V2 API needs; all three extend
[`Cin7Request`](../src/Requests/Cin7Request.php), which sets the bounded retry
policy for Cin7's throttling responses (429 and 503) and maps boolean and date
query values to the strings Cin7 expects.

## The three bases

| Base | Verb | Constructor | Request line |
|---|---|---|---|
| [`ListRequest`](../src/Requests/ListRequest.php) | GET | `(array $filters = [])` | `GET <path>?<filters>&page=1&limit=100` |
| [`KeyedRequest`](../src/Requests/KeyedRequest.php) | GET or DELETE | `(string $id, array $parameters = [])` | `<verb> <path>?<idKey>=<id>&<parameters>` |
| [`WriteRequest`](../src/Requests/WriteRequest.php) | POST or PUT | `(array\|Data $body = [])` | `<verb> <path>`, an array body as JSON verbatim, a data object as described in [data](data.md#write-bodies) |

A subclass declares its verb as a property default, its path through
`resolveEndpoint()`, and, for `ListRequest`, the envelope key its items sit
under (`protected string $listKey`). `KeyedRequest` declares `protected
string $idKey = 'ID'` only when the resource's identifier is not `ID` (e.g.
`SaleID`). None of the three bases are paginatable or keyed by default beyond
what their name says: only `ListRequest` implements `Paginatable`.

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

The `product` actions follow the same shape: `GetProduct` (a `ListRequest`
keyed `Products`), `PostProduct` and `PutProduct` (`WriteRequest`s; the PUT body
must carry `ID`, and `PutProduct` throws an `InvalidArgumentException` without one, while
`PostProduct` leaves `ID` out of its body because Cin7 ignores it on POST), all on `product`. The `customer` and `product` list requests' `dto()` is a
`list<CustomerData>` or `list<ProductData>`, and their POST and PUT `dto()` is the saved
record (`CustomerList.0`, `Products.0`).

The `ref` actions live under `src/Requests/Ref/`: `GetTax` (a `ListRequest` keyed
`TaxRuleList`), `PostTax` and `PutTax` (`WriteRequest`s; the PUT body carries `ID`), all
on `ref/tax`; and `GetCustomerCredits` (a `ListRequest` keyed `CustomerCredits`) on
`ref/customer/credits`.

The `moneyOperation` actions live under `src/Requests/MoneyOperation/`: `GetMoneyOperation`
(a `KeyedRequest` keyed `TaskID`), `DeleteMoneyOperation` (a `KeyedRequest` keyed `ID`; it takes
`Void` as a parameter), and `PostMoneyOperation` and `PutMoneyOperation` (`WriteRequest`s; the
PUT body carries `TaskID`), all on `moneyOperation`. Every one's `dto()` is a `MoneyTaskData`.

`GetMoneyTaskList` (a `ListRequest` keyed `MoneyTasks`) is on `moneyTaskList`, under
`src/Requests/MoneyTaskList/`; its `dto()` is a `list<MoneyTaskListData>`.

The `sale` actions live under `src/Requests/Sale/`: `GetSale` and `DeleteSale` (`KeyedRequest`s
keyed `ID`; the DELETE takes `Void` as a parameter) and `PostSale` and `PutSale`
(`WriteRequest`s; the PUT body carries `ID`, and `PutSale` leaves the POST-only `SaleType` out of it), all on `sale`. `sale` has no list action:
`GetSaleList` (a `ListRequest` keyed `SaleList`) is on `saleList`, under `src/Requests/SaleList/`.
Every `sale` request's `dto()` is a `SaleData`; `GetSaleList`'s is a `list<SaleListData>`.

The `sale/…` documents live under `src/Requests/Sale/`, one folder per path, 13 classes in all:

| Folder | Classes (`KeyedRequest` key, or `WriteRequest`) | `dto()` |
|---|---|---|
| `Order/` | `GetSaleOrder` (`SaleID`), `PostSaleOrder` | `SaleOrderData` |
| `Invoice/` | `GetSaleInvoice` (`SaleID`), `PostSaleInvoice`, `PutSaleInvoice`, `DeleteSaleInvoice` (`TaskID`) | `SaleInvoicesData` |
| `CreditNote/` | `GetSaleCreditNote` (`SaleID`), `PostSaleCreditNote`, `DeleteSaleCreditNote` (`TaskID`) | `SaleCreditNotesData` |
| `Payment/` | `GetSalePayment` (`SaleID`), `PostSalePayment`, `PutSalePayment`, `DeleteSalePayment` (`ID`) | `list<SalePaymentLinePartialData>` for the GET, `SalePaymentLinePartialData` for POST and PUT; none for the DELETE |

The write bodies are per verb where the reference's fields differ: `SaleInvoicePostData` and
`SaleInvoicePutData` for `sale/invoice`, `SaleCreditNotePostData` for `sale/creditnote`, and
`SalePaymentPostData` and `SalePaymentPutData` for `sale/payment`. Each makes the fields the
reference requires for that verb mandatory; see [data](data.md).

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
  are never added to a `KeyedRequest` or a `WriteRequest`. A page below 1 or a
  limit outside 1 to 1000 throws before anything is sent; see
  [page defaults](#page-defaults).
- **Boolean query values.** A `true`/`false` filter value goes out as the
  string `'true'`/`'false'`, not PHP's `1`/empty string, through
  `Cin7Request::queryValues()`.
- **Date query values.** A `DateTimeInterface` filter or parameter goes out in
  the reference's date format, ISO 8601 converted to UTC with milliseconds
  (`yyyy-MM-ddTHH:mm:ss.fff`, e.g. `2012-11-14T13:28:33.363`), through the same
  method. A date string is sent as given.
- **Identifier placement.** A `KeyedRequest` sends its identifier in the query
  string under `idKey` (`ID` by default). A `WriteRequest` sends no identifier
  of its own; the caller merges it into the body, as in
  [PUT identifiers](resources.md#put-identifiers).
- **Empty write.** `new PostCustomer()` with no body still sends a JSON body,
  the encoding of an empty array (`[]`), not a bodyless POST.

Query parameters on a `KeyedRequest` go out identifier first, then the
caller's own keys: `[$idKey => $id] + $parameters`, so the identifier wins
over a caller-supplied value under the same key. For example, a
`KeyedRequest` constructed with `('guid', ['ID' => 'ignored', 'Force' => 'true'])`
sends `ID=guid&Force=true`.

## Fields left out of write bodies

Cin7's reference marks some fields read-only, response-only, or available for one method only. A
`WriteRequest` subclass lists those in `$omit`, and they never reach the body, whether the caller
passed an array or a data object. A path is dot-separated and `*` stands for every list item. A
data object's body is also stripped of nulls and validated after the omission; see
[write bodies](data.md#write-bodies).

| Request | Left out |
| --- | --- |
| `PostCustomer`, `PutCustomer` | `LastModifiedOn`, `ChildCustomers`, `ProductPrices.*.ProductName` |
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
- Sends lowercase keys. Cin7 also accepts `Page` and `Limit`; a caller's are
  renamed to the lowercase spelling, so `['Page' => 5, 'Limit' => 20]` is sent as
  `page=5&limit=20`, never with both spellings, and `paginate()` reads the limit
  it sent.
- Treats a `null` value as absent, so the default is used.
- Enforces Cin7's bounds: `page` must be a whole number of at least 1, and
  `limit` one from 1 to `PageDefaults::LIMIT_MAX` (1000), the largest page Cin7
  serves. Anything else throws an `InvalidArgumentException` from `send()`
  before the request goes out. The bound matters beyond Cin7 rejecting the
  call: the paginator counts pages by the limit it sent, so a limit Cin7 cut
  short would end the walk early. `Cin7Paginator` applies the same checks to
  `perPageLimit()` and `startPage()`.
- Appends the defaults after the caller's keys, which keep their order.
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
the error with `Ipsocode\Cin7\Data\ErrorData`:

```php
use Hypervel\Saloon\Exceptions\Request\RequestException;
use Ipsocode\Cin7\Data\ErrorData;

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

`Cin7Request`'s constructor reads `cin7.retry.*`, so a subclass that adds
constructor parameters must call `parent::__construct()`. PHP initializes
property defaults before any constructor body runs, so `$this->method()`
is always the declared verb; a subclass must never assign `$this->method` in
its own constructor.
