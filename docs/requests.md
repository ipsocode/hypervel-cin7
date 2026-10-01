# Requests

Every call to Cin7 is one of five generic request classes in
`Ipsocode\Cin7\Requests`, each built from an [`Endpoint`](endpoints.md) case and
sent through the `Cin7Connector` singleton. The request decides the verb, where
the GUID and parameters go, and whether the page defaults are added; the
endpoint supplies the path and the GUID keys. All five extend
[`Cin7Request`](../src/Requests/Cin7Request.php), which rejects verbs the
endpoint does not accept and sets the bounded 503 retry policy.

## The five requests

| Request | Constructor | Request line |
|---|---|---|
| `ListRecords` | `(Endpoint $endpoint, array $parameters = [])` | `GET <path>?<parameters>&page=1&limit=100` |
| `FindRecord` | `(Endpoint $endpoint, string $guid, array $parameters = [])` | `GET <path>?<parameters>&<guidKey>=<guid>&page=1&limit=100` |
| `CreateRecord` | `(Endpoint $endpoint, array $data = [])` | `POST <path>`, body `$data` as JSON |
| `UpdateRecord` | `(Endpoint $endpoint, string $guid, array $data = [])` | `PUT <path>`, body `$data` plus `<guidKey>: <guid>` as JSON |
| `DeleteRecord` | `(Endpoint $endpoint, string $guid, array $parameters = [])` | `DELETE <path>?<parameters>&ID=<guid>&page=1&limit=100` |

```php
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Requests\CreateRecord;
use Ipsocode\Cin7\Requests\DeleteRecord;
use Ipsocode\Cin7\Requests\FindRecord;
use Ipsocode\Cin7\Requests\ListRecords;
use Ipsocode\Cin7\Requests\UpdateRecord;

public function __construct(private readonly Cin7Connector $cin7) {}

// GET customer?page=1&limit=100
$all = $this->cin7->send(new ListRecords(Endpoint::Customer))->json();

// GET sale/invoice?SaleID=…&page=1&limit=100
$one = $this->cin7->send(new FindRecord(Endpoint::SaleInvoice, $guid))->json();

// POST customer with body {"Name":"ACME"}
$new = $this->cin7->send(new CreateRecord(Endpoint::Customer, ['Name' => 'ACME']))->json();

// PUT customer with body {"Name":"ACME Ltd","ID":"…"}
$this->cin7->send(new UpdateRecord(Endpoint::Customer, $guid, ['Name' => 'ACME Ltd']));

// DELETE sale/invoice?ID=…&page=1&limit=100
$this->cin7->send(new DeleteRecord(Endpoint::SaleInvoice, $guid));
```

`json()` returns Cin7's response decoded to an associative array. Every request
exposes the endpoint it targets through `endpoint()`, so code holding a
request (a pool, a retry wrapper, a log line) can name it without parsing the
URL. `ListRecords` is the only request that implements `Paginatable`; walking
every page is covered in [pagination](pagination.md).

## Wire protocol

These are the requests Cin7 receives.

- **Base URL.** `https://inventory.dearsystems.com/ExternalApi/v2/`, with the
  endpoint path appended relative to it, e.g.
  `https://inventory.dearsystems.com/ExternalApi/v2/sale/invoice`.
- **Headers.** Every request carries `Content-Type: application/json`,
  `api-auth-accountid` and `api-auth-applicationkey`, the last two from
  [configuration](configuration.md).
- **Parameters.** GET and DELETE carry their parameters in the query string.
  POST and PUT carry theirs as a raw JSON body and send no query string.
- **Page defaults.** `page=1` and `limit=100`, lowercase, are added to the
  query string of every list, find and delete when the caller has not set
  them. They are never added to a create or update body. A DELETE carries
  `page` and `limit` like a read.
- **GUID placement.**

  | Request | Where the GUID goes | Key |
  |---|---|---|
  | `FindRecord` | Query string | `guidKey()`: `ID`, `SaleID` or `CustomerID` by endpoint |
  | `UpdateRecord` | JSON body | `guidKey()`, the same key a find uses |
  | `DeleteRecord` | Query string | `deleteGuidKey()`: `ID` on every endpoint modelled |

  Every endpoint modelled here deletes under `ID`, including the `sale/*`
  ones that find by `SaleID`. The keys per endpoint are in
  [endpoints](endpoints.md).
- **Verb gate.** A verb the endpoint does not accept is rejected while the
  request is constructed, before any HTTP call. See [Errors](#errors).
- **Empty create.** `new CreateRecord($endpoint)` with no data still sends a
  JSON body, the encoding of an empty array (`[]`), not a bodyless POST.

Query parameters go out in this order: the caller's own keys, then the GUID key
(find and delete), then `page` and `limit`. If the caller's parameters already
hold the GUID key, the GUID replaces that value where it stands, as in
[GUID precedence](#guid-precedence). For example
`new DeleteRecord(Endpoint::Sale, $guid, ['Force' => 'true'])` sends
`DELETE sale?Force=true&ID=…&page=1&limit=100`.

## GUID precedence

The request assigns the GUID after copying the caller's parameters or data, so
it wins over a caller-supplied value under the same key:

```php
// GET customer?ID=guid-3&page=1&limit=100
new FindRecord(Endpoint::Customer, 'guid-3', ['ID' => 'ignored']);

// PUT customer with body {"ID":"guid-8"}
new UpdateRecord(Endpoint::Customer, 'guid-8', ['ID' => 'ignored']);
```

## Page defaults

Cin7 expects `page` and `limit` on every read, so the package always sends them.
[`PageDefaults::apply()`](../src/PageDefaults.php) adds the defaults to list,
find and delete parameters:

- It sets `page` to `PageDefaults::PAGE` (`1`) and `limit` to
  `PageDefaults::LIMIT` (`100`) only when the caller has not. Caller values
  win.
- The spelling is lowercase. Cin7 also accepts `Page` and `Limit`, but those
  keys do not count as set: `['Page' => 5, 'Limit' => 20]` is sent as
  `Page=5&Limit=20&page=1&limit=100`. Use the lowercase keys to page by hand.
- A `null` value is treated as absent, so the default is used.
- The defaults are appended after the caller's keys, which keep their order.
- `PAGE` and `LIMIT` are public constants, so code paging by hand can read them
  instead of hard-coding `1` and `100`.
- The caller's array is not modified.

## Errors

Every request uses Saloon's `AlwaysThrowOnErrors`, so a 4xx or 5xx response
throws once any retries are spent:

| Exception | When | Extends | Carries |
|---|---|---|---|
| `Hypervel\Saloon\Exceptions\Request\ClientException` | 4xx, e.g. `403 Incorrect credentials!` for a bad account ID or key | `Hypervel\Saloon\Exceptions\Request\RequestException` | The full `Response`: `response()`, `status()`, `body()`, `pendingRequest()` |
| `Hypervel\Saloon\Exceptions\Request\ServerException` | 5xx, including a 503 that outlasted the retries | `Hypervel\Saloon\Exceptions\Request\RequestException` | The full `Response`, as above |
| `Hypervel\Saloon\Exceptions\Request\FatalRequestException` | Transport failure: DNS, refused connection, timeout | `Hypervel\Saloon\Exceptions\SaloonException` | No response; `pendingRequest()` and the original exception as `getPrevious()` |
| [`Ipsocode\Cin7\Exceptions\MethodNotAllowedException`](../src/Exceptions/MethodNotAllowedException.php) | Constructing a request whose verb the endpoint does not accept | `Hypervel\Saloon\Exceptions\SaloonException` | The message only |

Saloon's `RequestException` extends `Hypervel\Http\Client\RequestException`,
not `SaloonException`, so a single `catch (SaloonException)` covers the
transport failure and the verb gate but not 4xx or 5xx responses.

`MethodNotAllowedException` is thrown from the request constructor, so nothing
is sent. Its message names the verb and the endpoint value the caller passed:

```text
Method [DELETE] is not allowed on the [customer] endpoint.
```

It is an `Exception`, unlike the `ValueError` (an `Error`) that
`Endpoint::fromAccessor()` throws for an unknown endpoint name: an unknown name
is a programming mistake, while an unsupported verb is a request the caller can
make differently.

Cin7 also reports some failures as an `ErrorCode` inside a 200 body. The package
returns that body decoded like any other and leaves the decision to the caller.

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

  $request = new ListRecords(Endpoint::Customer); // reads the values above
  ```

The wait goes through `Hypervel\Support\Sleep`, which suspends only the calling
coroutine. A 503 off the wire also puts the account into the connector's
5-second rate limit cooldown; see [connector](connector.md).

## Writing a request class

The five requests are `final`. A new kind of request extends `Cin7Request`,
declares its verb as a property default and calls the parent constructor:

```php
use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Requests\Cin7Request;

final class MyRequest extends Cin7Request
{
    protected Method $method = Method::PUT;

    public function __construct(Endpoint $endpoint, protected readonly string $guid)
    {
        parent::__construct($endpoint);
    }

    // defaultQuery() or defaultBody() as the request needs.
}
```

The verb gate in `Cin7Request::__construct()` reads `$this->method()`. PHP
initializes property defaults before any constructor body runs, so the gate
sees the declared verb. A subclass must never assign `$this->method` in its own
constructor: assigned after `parent::__construct()`, the new verb skips the
gate.
