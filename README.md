# hypervel-cin7

A [Cin7 Core](https://www.cin7.com/) API client for
[Hypervel](https://github.com/hypervel/components), built on `hypervel/saloon`.

> [!WARNING]
> **Development only — do not use this package in production until Hypervel 0.4
> is released.**
>
> It is built for Hypervel 0.4, which has no release yet: 0.4 exists only as the
> `0.4.x-dev` branch of [`hypervel/components`](https://github.com/hypervel/components),
> and this package is developed and tested against that moving branch. Until 0.4
> ships, anything here can change without a deprecation period — the API, the
> configuration and the requests it sends included. Use it to evaluate or to
> build against Hypervel 0.4, and pin the version you tested.

```php
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Endpoint;
use Ipsocode\Cin7\Requests\FindRecord;

public function __construct(private readonly Cin7Connector $cin7) {}

// GET sale/invoice?SaleID=…&page=1&limit=100
$invoice = $this->cin7->send(new FindRecord(Endpoint::SaleInvoice, $saleId))->json();
```

## What this is

Cin7 Core — formerly DEAR Inventory — exposes a REST API at
`inventory.dearsystems.com`. This package is a client for it that is safe to run
in long-lived Swoole workers: one shared connector, a bounded 503 retry, and
throttling through the framework's rate limiter, all on `hypervel/saloon`.

It replaces [`eighteen73/dear-api`](https://github.com/eighteen73/dear-api),
which has had no upstream commit since 2023-05-09 and whose 74 endpoint classes,
per-request Guzzle clients, unbounded 503 recursion and `getenv()` fallbacks are
all things a Swoole application should not be carrying. The wire protocol is
kept exactly — see
[Behavior inherited from `eighteen73/dear-api`](#behavior-inherited-from-eighteen73dear-api) —
and the few deliberate differences are listed under
[Deliberate behavior changes](#deliberate-behavior-changes).

This is an independent package. It is not affiliated with or endorsed by Cin7.

## Requirements

- PHP 8.4 or newer (CI runs 8.4 and 8.5)
- Hypervel 0.4, which today means `hypervel/components` at `0.4.x-dev`. The
  package requires `hypervel/contracts`, `hypervel/saloon` and
  `hypervel/support` `^0.4`; `hypervel/components` provides all of them.
- A Cin7 Core account with API access — its account ID and an application key

## Installation

The package is not on Packagist, so add this repository to your application's
Composer repositories first:

```sh
composer config repositories.hypervel-cin7 vcs https://github.com/ipsocode/hypervel-cin7
composer require ipsocode/hypervel-cin7
php artisan vendor:publish --tag=cin7-config
```

Hypervel 0.4 is only available as a dev branch, so your application's
`composer.json` must already allow it: `"minimum-stability": "dev"` together
with `"prefer-stable": true`. Tags are not re-tested as `0.4.x-dev` moves on,
and neither is `main` between changes: each change is tested against the
`0.4.x-dev` of its day before it merges. To pick up changes as they land,
require `ipsocode/hypervel-cin7:dev-main` instead. Each release's notes,
breaking changes first, are on the
[Releases](https://github.com/ipsocode/hypervel-cin7/releases) page.

The service provider (`Ipsocode\Cin7\Cin7ServiceProvider`) is discovered
through the package's `extra.hypervel` block, and `hypervel/saloon`'s own
provider through the components manifest, so there is nothing to register and
no Saloon wiring to do. Publishing the config is optional: the packaged defaults
are merged in either way. The credentials go in your environment:

```dotenv
CIN7_ACCOUNT_ID=
CIN7_APPLICATION_KEY=
```

## Configuration

`config/cin7.php`:

| Key | Env | Default | Meaning |
|---|---|---|---|
| `account_id` | `CIN7_ACCOUNT_ID` | — | `api-auth-accountid` header |
| `application_key` | `CIN7_APPLICATION_KEY` | — | `api-auth-applicationkey` header |
| `rate_limit.max` | `CIN7_RATE_MAX` | `60` | calls allowed per window; `0` disables throttling |
| `rate_limit.period` | `CIN7_RATE_PERIOD` | `60` | window length in seconds; `0` also disables throttling |
| `rate_limit.store` | `CIN7_RATE_STORE` | — | rate limiter store; unset uses the application's default — see [Rate limiting](#rate-limiting-moved-into-the-connector) |
| `retry.times` | `CIN7_RETRY_TIMES` | `4` | total attempts on a 503, not extra ones |
| `retry.delay_ms` | `CIN7_RETRY_DELAY_MS` | `5000` | gap between attempts |

Values are read through `config()` at container-resolve time and passed to the
connector explicitly. Nothing here calls `env()` or `getenv()` at request time.

You may add your own keys to the published file — `mergeConfigFrom()` keeps the
published copy authoritative on shared keys and leaves the extras alone.

## Usage

Inject `Cin7Connector` and send one of the five generic requests.

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

// POST customer, raw JSON body, no page/limit
$new = $this->cin7->send(new CreateRecord(Endpoint::Customer, ['Name' => 'ACME']))->json();

// PUT customer, GUID merged into the body under the endpoint's GUID key
$this->cin7->send(new UpdateRecord(Endpoint::Customer, $guid, ['Name' => 'ACME Ltd']));

// DELETE sale/invoice?ID=…&page=1&limit=100
$this->cin7->send(new DeleteRecord(Endpoint::SaleInvoice, $guid));
```

The connector is registered as a singleton. It holds only readonly scalars and
is never mutated per request, so sharing one instance across coroutines for a
worker's lifetime is safe.

### Pagination

`ListRecords` is `Paginatable`, and the connector implements `HasPagination`, so
a listing walks every page instead of only the first:

```php
use Hypervel\Saloon\Http\Response;

// Every customer, fetched one page at a time.
foreach ($this->cin7->paginate(new ListRecords(Endpoint::Customer))->items() as $customer) {
    // ...
}

// Or every page sent concurrently through the framework's bounded coroutine
// pool — the connector's own rate limiter still admits each call, so this
// cannot outrun the account limit, it just queues past it.
/** @var array<int, Response> $responses */
$responses = $this->cin7->paginate(new ListRecords(Endpoint::Customer))
    ->perPageLimit(100)
    ->pool(concurrency: 5);
```

The paginator reads Cin7's `{Total, Page, <Thing>List}` envelope: `Total` is the
full matching record count, `Page` is the page just served, and the last page is
derived by dividing the two — Cin7 never says so directly. `perPageLimit()`
sends `limit` (lowercase, matching the page defaults below); leaving it unset
lets the request's own default (100) stand.

### Endpoints

`Endpoint` is a backed enum whose value is the accessor name the old package
exposed, so callers that already hold endpoint strings can pass them through
`Endpoint::fromAccessor()` unchanged.

| Case | Value | Path | GUID key (find/update) | Delete key | Verbs beyond GET |
|---|---|---|---|---|---|
| `Customer` | `customer` | `customer` | `ID` | `ID` | POST, PUT |
| `Product` | `product` | `product` | `ID` | `ID` | POST, PUT |
| `Sale` | `sale` | `sale` | `ID` | `ID` | POST, PUT, DELETE |
| `SaleInvoice` | `saleInvoice` | `sale/invoice` | `SaleID` | `ID` | POST, DELETE |
| `SaleOrder` | `saleOrder` | `sale/order` | `SaleID` | `ID` | POST |
| `SaleCreditNote` | `saleCreditNote` | `sale/creditnote` | `SaleID` | `ID` | POST, DELETE |
| `SalePayment` | `salePayment` | `sale/payment` | `ID` | `ID` | POST, PUT, DELETE |
| `SaleList` | `saleList` | `saleList` | `SaleID` | `ID` | — |
| `Tax` | `tax` | `ref/tax` | `ID` | `ID` | POST, PUT |
| `MoneyOperation` | `moneyOperation` | `moneyOperation` | `ID` | `ID` | POST, PUT, DELETE |
| `CustomerCredits` | `customerCredits` | `ref/customer/credits` | `CustomerID` | `ID` | — |

Cin7's remaining ~64 endpoints are added as cases on demand — three strings
each, never a new class. Two quirks are worth carrying over when those land:
`Account` is keyed by `Code` for find, update *and* delete — the only upstream
endpoint whose delete key is not `ID` — and `ProductMarkupPrices` finds and
updates under `ProductID`.

> **`CustomerCredits` is the one row not transcribed from vendor code** —
> `eighteen73/dear-api` never exposed `ref/customer/credits`. Verify its GUID
> key against the Cin7 API reference before relying on `FindRecord` for it.

### Error surface

Every request uses `AlwaysThrowOnErrors`, so a non-2xx response throws:

- `Hypervel\Saloon\Exceptions\Request\ClientException` (4xx) and
  `…\ServerException` (5xx), both subclasses of `…\RequestException`. Unlike
  the package this replaces, these always carry the full `Response` —
  `$e->response()`, `$e->status()`, `$e->body()`.
- `Hypervel\Saloon\Exceptions\Request\FatalRequestException` for transport
  failures (DNS, refused connection, timeout). It extends `SaloonException`
  directly and carries **no** response.

`MethodNotAllowedException` is thrown while the request is being
*constructed* — before any HTTP — when a verb is not supported by its endpoint.

Cin7 also signals some failures with an `ErrorCode` inside a 200 body. That is a
consumer policy decision, so this package returns the decoded array and leaves
it to the caller.

## Behavior inherited from `eighteen73/dear-api`

Preserved exactly, because it is wire protocol:

- Base URL `https://inventory.dearsystems.com/ExternalApi/v2/`, endpoint path
  appended relative.
- `Content-Type: application/json`, `api-auth-accountid` and
  `api-auth-applicationkey` on every request.
- GET/DELETE parameters in the query string; POST/PUT parameters as a raw JSON
  body.
- `page=1` and `limit=100` (lowercase) injected into **list, find and delete**
  query strings when absent — but never into create/update bodies. Yes, a
  DELETE really does carry `page` and `limit`; Cin7 has always seen them.
- Find sends the GUID as a **query parameter**, update merges it into the
  **body**, delete sends it as a query parameter under `deleteGuidKey()` —
  which is `ID` for every endpoint modelled here, including the `sale/*` ones
  that *find* by `SaleID`.
- Verb gating happens before any HTTP call.

## Deliberate behavior changes

| Was | Now |
|---|---|
| 503 → `sleep(5)` then unbounded recursion; a throttled account pinned the coroutine forever | Bounded retry: 4 attempts, 5 s apart, 503-only. After exhaustion the error surfaces like any other failure |
| A fresh Guzzle client per request with **no timeout at all** | The shared `saloon` HTTP connection: `connect_timeout` 10 s, `timeout` 30 s, cURL transport sharing. **A call that used to hang forever now fails at 30 s** |
| `getenv('DEAR_ACCOUNT_ID')` fallback — unsafe under Swoole | Credentials constructor-injected from `config()` |
| 5xx exceptions carried an uninitialized `$responseBody`; reading it threw | Saloon responses always carry the body |
| `json_encode()` silently produced `false` on bad input | JSON encoding throws |
| `ref/customer/credits` not exposed at all | `Endpoint::CustomerCredits` |

### Rate limiting moved into the connector

Throttling now runs through the framework rate limiter (`HasRateLimits`), keyed
`cin7:api:<accountId>`, and **waits** for capacity instead of proceeding once a
give-up threshold is passed. Waiting goes through `Hypervel\Support\Sleep`,
whose native sleep is Swoole-hooked, so it suspends only the calling coroutine.
A 503 also puts the account into a 5-second cooldown, keyed per account like the
limit, so the coroutines sending for it wait the throttle out instead of adding
to it.

The limit is only account-wide if every worker and server that calls Cin7
shares the limiter store. With `rate_limit.store` unset, the connector uses
`saloon.rate_limiter.store`, and when that is unset too, the application's
default store (`rate-limiter.default`) — `database` unless `RATE_LIMITER_STORE`
says otherwise:

| Store | Shared by |
|---|---|
| `database` (the framework default) | every worker and server using that database |
| `redis` | every worker and server using that Redis connection |
| `swoole` | the workers of one server only — the effective limit multiplies by the server count |
| `worker-array` (the Testbench default) | one worker only — the effective limit multiplies by the worker count; meant for tests |

With the `database` store, the application needs the framework's `rate_limits`
table (`php artisan make:rate-limiter-table` in an application that predates
it), and a Cin7 call made while the limiter's database connection is inside a
transaction throws a `LogicException`. If your application calls Cin7 from
inside transactions, give the store a connection of its own
(`rate-limiter.stores.database.connection`) or use `redis`.

> **Known regression.** If the limiter store is unreachable — the database, or
> Redis if you chose it — a consume failure now throws inside `send()` rather
> than being swallowed. Callers that previously degraded to *unthrottled but
> working* will instead see failed calls. There is no clean connector hook to
> restore the old behavior — the store is touched by `SaloonManager` at consume
> time, not by `resolveRateLimits()`. If parity is ever required, keep a local
> throttle in the consumer and set `rate_limit.max` to `0`.

## What this package deliberately does not do

- **No caching.** Response caching, cache-key shape and cache-hit logging
  semantics are consumer policy; `Cacheable`/`HasCaching` can be adopted later
  once a second consumer's needs are known.
- **No DTOs.** Cin7 responses stay associative arrays; consumers already have a
  typed domain layer.
- **No request logging.** Instrumentation writes consumer-owned models.

## Testing an application that uses it

The package keeps no static state, so there is nothing to register for the
framework's between-test reset. Fake Cin7 the way this package's own suite
does, through Saloon's global mock client — a faked send never touches the rate
limiter either, so the tests need no limiter store:

```php
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;

$mock = Saloon::fake([
    MockResponse::make(['Total' => 1, 'Page' => 1, 'CustomerList' => [['ID' => '…', 'Name' => 'ACME']]]),
]);

// ... exercise the code that calls Cin7 ...

$mock->assertSentCount(1);
```

## Contributing

The development setup, the checks CI runs, the coroutine-safety rules every
change is held to, and how releases are cut are in
[CONTRIBUTING.md](CONTRIBUTING.md). Report security issues privately, as
described in [SECURITY.md](.github/SECURITY.md), rather than in a public issue.

## Credits

The wire protocol, the endpoint table and the page-defaults helper are ported
from [`eighteen73/dear-api`](https://github.com/eighteen73/dear-api), by Umair
Mahmood and its contributors, which is MIT-licensed. The client itself is a
rewrite on `hypervel/saloon` rather than a copy of that code.

## License

MIT. See [LICENSE](LICENSE), which carries the copyright notices of this package
and of `eighteen73/dear-api`.
