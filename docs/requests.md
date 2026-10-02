# Requests

Every call to Cin7 is a request class in `Ipsocode\Cin7\Requests`, one per API
action, grouped under a [resource](resources.md) such as `CustomerResource`.
Three bases cover the shapes Cin7's V2 API needs; all three extend
[`Cin7Request`](../src/Requests/Cin7Request.php), which sets the bounded 503
retry policy and maps boolean query values to the strings Cin7 expects.

## The three bases

| Base | Verb | Constructor | Request line |
|---|---|---|---|
| [`ListRequest`](../src/Requests/ListRequest.php) | GET | `(array $filters = [])` | `GET <path>?<filters>&page=1&limit=100` |
| [`KeyedRequest`](../src/Requests/KeyedRequest.php) | GET or DELETE | `(string $id, array $parameters = [])` | `<verb> <path>?<idKey>=<id>&<parameters>` |
| [`WriteRequest`](../src/Requests/WriteRequest.php) | POST or PUT | `(array $body = [])` | `<verb> <path>`, body `$body` as JSON, verbatim |

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
  are never added to a `KeyedRequest` or a `WriteRequest`.
- **Boolean query values.** A `true`/`false` filter value goes out as the
  string `'true'`/`'false'`, not PHP's `1`/empty string, through
  `Cin7Request::queryValues()`.
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

## Page defaults

Cin7 expects `page` and `limit` on every list read, so `ListRequest` always
sends them. [`PageDefaults::apply()`](../src/PageDefaults.php):

- Sets `page` to `PageDefaults::PAGE` (`1`) and `limit` to
  `PageDefaults::LIMIT` (`100`) only when the caller has not. Caller values
  win.
- Adds lowercase keys. Cin7 also accepts `Page` and `Limit`, and those count as
  set: `['Page' => 5, 'Limit' => 20]` is sent as `Page=5&Limit=20`, never with
  both spellings.
- Treats a `null` value as absent, so the default is used.
- Appends the defaults after the caller's keys, which keep their order.
- Exposes `PAGE` and `LIMIT` as public constants, so code paging by hand can
  read them instead of hard-coding `1` and `100`.
- Never mutates the caller's array.

## Errors

Every request uses Saloon's `AlwaysThrowOnErrors`, so a 4xx or 5xx response
throws once any retries are spent:

| Exception | When | Extends | Carries |
|---|---|---|---|
| `Hypervel\Saloon\Exceptions\Request\ClientException` | 4xx, e.g. `403 Incorrect credentials!` for a bad account ID or key | `Hypervel\Saloon\Exceptions\Request\RequestException` | The full `Response`: `response()`, `status()`, `body()`, `pendingRequest()` |
| `Hypervel\Saloon\Exceptions\Request\ServerException` | 5xx, including a 503 that outlasted the retries | `Hypervel\Saloon\Exceptions\Request\RequestException` | The full `Response`, as above |
| `Hypervel\Saloon\Exceptions\Request\FatalRequestException` | Transport failure: DNS, refused connection, timeout | `Hypervel\Saloon\Exceptions\SaloonException` | No response; `pendingRequest()` and the original exception as `getPrevious()` |

Saloon's `RequestException` extends `Hypervel\Http\Client\RequestException`,
not `SaloonException`, so a single `catch (SaloonException)` covers only the
transport failure, not a 4xx or 5xx response.

Cin7 also reports some failures as an `ErrorCode` inside a 200 body. The
package returns that body decoded like any other and leaves the decision to
the caller.

A request body that cannot be encoded as JSON throws from `send()` before
anything goes out: Saloon throws a `BodyException` when `json_encode()` fails,
and an `InvalidArgumentException` for a value that does not resolve to arrays,
scalars and `null`.

## Retry policy

A 503 is how Cin7 signals throttling, and it is the only response retried:

- **503 only.** Any other 4xx or 5xx, 500 included, throws on the first
  attempt. A `FatalRequestException` (DNS, refused connection, timeout) is not
  retried either. A bad credential comes back as `403 Incorrect credentials!`
  on every call, so retrying it would only turn a configuration mistake into a
  delay of about 15 s (three 5 s waits) per call.
- **Bounded.** `cin7.retry.times` is the total number of attempts, not the
  number of extra ones, and `cin7.retry.delay_ms` is the wait between them. The
  defaults, `4` and `5000`, mean at most four attempts with three 5 s waits.
  When the attempts run out, the last 503 throws as a `ServerException`.
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
calling coroutine. A 503 off the wire also puts the account into the
connector's 5-second rate limit cooldown; see [connector](connector.md).

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
