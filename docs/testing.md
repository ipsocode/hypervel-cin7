# Testing

This page has two parts. The first is for an application that uses the
package: how to fake Cin7 in its tests. The second explains how the package's
own suite is put together: what is a Unit test and what is a Feature test,
where the test environment comes from, why the rate-limit tests call the
connector's hooks directly, and what the Workbench application is for. The
commands that run the suite and the CI checks are in
[CONTRIBUTING.md](../CONTRIBUTING.md).

## Testing an application that uses the package

Fake Cin7 through Saloon's global mock client, with the responses `Ipsocode\Cin7\Testing\Cin7Fake`
builds, as the package's own suite does:

```php
use Hypervel\Saloon\Facades\Saloon;
use Ipsocode\Cin7\Testing\Cin7Fake;

$mock = Saloon::fake([
    Cin7Fake::list('CustomerList', [['ID' => '…', 'Name' => 'ACME']]),
]);

// ... exercise the code that calls Cin7 ...

$mock->assertSentCount(1);
```

`$mock->lastPendingRequest()` is the last request as it went out, so a test can
assert on its `headers()`, `queryParameters()` and `body()`.

- **Shape the fakes like Cin7.** A list comes back in the
  `{Total, Page, <Thing>List}` envelope described in
  [pagination](pagination.md#the-list-envelope), and a failure as the Error
  Model, `{ErrorCode, Exception}`; a fake with any other shape tests code
  against a body Cin7 never sends. `Cin7Fake` builds both, so a test does not
  spell the keys out. A faked Error Model throws even with a 200, as the real
  one does (see [errors](requests.md#errors)).
- **No reference examples ship.** The builders give a body its envelope and
  status; the record inside is the test's own. The V2 reference's full examples
  are the package's test data, not part of what an install carries.
- **Fixtures for the typed bodies.** In the package's own suite, those examples are JSON files under
  `workbench/fixtures/`, one folder per API path: `Cin7Payloads::load('ref/tax',
  'get.response')` reads `workbench/fixtures/ref/tax/get.response.json`, and named helpers
  such as `Cin7Payloads::taxList()` wrap the older ones. `DataCatalogueTest` asserts each
  `dto()` round-trips its fixture, so every key is modelled under its wire name. Testbench does not auto-discover
  `Hypervel\Data\DataServiceProvider`, so `testbench.yaml` lists it, as it does Saloon's.
- **Nothing to reset.** The package keeps no static state, so there is nothing
  to register for the framework's between-test reset. The mock client lives on
  the container's `SaloonManager` singleton and goes with each test's
  application.
- **No limiter store needed.** A faked send never touches the rate limiter:
  Saloon enforces limits only when no fake matched, and records the throttling
  cooldown only for responses that came off the wire.
- **Faking a 429 or 503.** `Cin7Fake::limitReached()` is the 429 and
  `Cin7Fake::throttled()` the 503, which comes with no `Retry-After`;
  `limitReached(30)` adds one. The request retries it, 4 attempts 5 seconds apart
  by default (see [retry policy](requests.md#retry-policy)); a backoff or jitter
  changes those gaps, and `Ipsocode\Cin7\Support\Jitter` can be bound with
  `$this->instance()` to pin the jitter. Call
  `Hypervel\Support\Sleep::fake()` so the waits take no time, and fake one
  response per attempt; or set `cin7.retry.times` to `1` before constructing
  the request, since the retry policy is read in the constructor.
- **Data object bodies are validated.** A body built from a data class must
  pass its rules, so a fake GUID such as `'guid-1'` in a `#[Uuid]` field throws
  a `ValidationException` before the mock sees the request. Use a real-shaped
  GUID, or an array body, which is sent as given.

The builders, all returning a `MockResponse`:

| Builder | Builds |
|---|---|
| `Cin7Fake::list($listKey, $items, $page = 1, $total = null)` | `{Total, Page, <listKey>}`; `Total` defaults to the item count, so a multi-page fake passes it |
| `Cin7Fake::listWithoutTotal($listKey, $items, $page = 1)` | `{Page, <listKey>}`, the envelope of `ref/customer/credits` and `ref/supplier/deposits` (see [pagination](pagination.md#an-envelope-with-no-total)) |
| `Cin7Fake::record($body)` | the body as given, with a 200 |
| `Cin7Fake::error($message, $code = 400, $status = null)` | the Error Model; the status is the code unless `$status` names another, so `status: 200` is the Error Model Cin7 sometimes sends with a 200 |
| `Cin7Fake::throttled()` | the 503, with no `Retry-After` |
| `Cin7Fake::limitReached($retryAfter = null)` | the 429, with a `Retry-After` when given |
| `Cin7Fake::credentialsRejected()` | the `403 Incorrect credentials!` |

A fake can also be keyed by request class, with a closure that builds the
response from the `PendingRequest`:

```php
use Hypervel\Saloon\Http\PendingRequest;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;

Saloon::fake([
    GetCustomer::class => fn (PendingRequest $request): MockResponse => Cin7Fake::list(
        'CustomerList',
        [/* … */],
        page: (int) $request->queryParameters()['page'],
        total: 3,
    ),
]);
```

## The package's own suite

### Unit and Feature

The split is enforced rather than conventional.

| Suite | Covers | Boots the application |
|---|---|---|
| `tests/Unit` | `PageDefaults` | No |
| `tests/Feature` | the container binding, the config merge, request and resource construction, every faked send, the shipped `Cin7Fake`, the Workbench application | Yes |

Every Unit test method carries `#[UnitTest]`
(`Hypervel\Foundation\Testing\Attributes\UnitTest`), so the framework never
builds a Testbench application for it, and anything that reaches for a facade
or the container fails outright. The line follows what the code touches, not
how simple it looks: the request classes read `config('cin7.retry.*')` in their
constructor, so their tests are Feature tests.

Feature tests extend [`tests/TestCase.php`](../tests/TestCase.php), which
provides `connector()` (the connector as the container resolves it, built from
config) and `pendingRequestFor()` (a `PendingRequest` assembled the way Saloon
assembles one, for the rate-limit hooks, defaulting to a `GetCustomer`
request). A test that calls `new Cin7Connector(...)` itself does so to pin a
constructor argument the config path cannot reach.

### Every send is faked

Every send in the suite goes through Saloon's mock client; nothing reaches the
live Cin7 API. The request tests assert on the `PendingRequest` the connector
built (headers, URL, query and body as they would have gone out) rather than on
the request object's own accessors.

### The catalogue

`RequestCatalogueTest`, `ResourceCatalogueTest` and `DataCatalogueTest` run the same
assertions over one row per request, resource method and data class. The rows live in one
file per API path under `tests/Fixtures/Catalogue/`: `sale/invoice.php` holds the rows for
`sale/invoice`, and `moneyTask.php` those of `moneyOperation`, which the package names after
the Money Task. Each file returns its rows by kind, and
[`Catalogue::rows()`](../tests/Catalogue.php) merges every file's rows of a kind:

| Kind | A row is |
|---|---|
| `requests` | a request class, its arguments, and the method, path, query and body it sends; `… with data` rows send a data object body |
| `resources` | a resource call, keyed `<resource path> <method>` (`sale payment put`), and the request it sends |
| `dtos` | a request, its fixture and the data class `dto()` returns, with where the record sits in the fixture |
| `bodies` | a body class and the reference's request example it round-trips |
| `missing` | a class and a payload without one of its required fields, which cannot be built |
| `required` | the fields of a class the reference requires |
| `omitted` | a request, the body given, and the body sent without its `$omit` fields |

A key two files share throws, and three tests prove nothing is left out: every concrete
request class has a `requests` row, every resource method that sends a request has a
`resources` row, and every model is reached from a `dtos`, `bodies` or `required` row,
directly or through a property at any depth.

### The test environment

The environment the suite boots into is defined in two files that must stay in
step:

| File | Applied to |
|---|---|
| [`testbench.yaml`](../testbench.yaml), `env` | the Testbench CLI: `composer test`, `vendor/bin/testbench` |
| [`phpunit.xml`](../phpunit.xml), `<php><env>` | PHPUnit test methods, which Testbench's `env` block does not reach |

Both spell out every `CIN7_*` variable except `CIN7_RATE_STORE`, so
`config/cin7.php`'s `env()` calls are under test, not only its defaults.
`CIN7_RATE_STORE` stays unset so the limiter store falls back as it does by
default; a test that needs a store sets `cin7.rate_limit.store`. Nothing is set
through `defineEnvironment()`; a test that needs a different value sets the
config key, which is the path a published config takes too.

The [sync](sync.md)'s table needs a database, so both files also set
`DB_CONNECTION=sqlite` with `DB_DATABASE=:memory:`, which starts every test
empty and keeps the suite parallel-safe, and `CACHE_STORE=array` for its module
locks (the skeleton's `database` store has no `cache_locks` table there).
`CIN7_SYNC` stays unset, so the sync is off as it is by default. The sync tests
extend `tests/Feature/Sync/SyncTestCase`, which turns it on with
`#[WithConfig('cin7.sync.enabled', true)]`: that is applied before the provider
boots, so the provider loads the migration and registers the schedule as it
would in an application. The case also uses `RefreshDatabase`, fakes `Sleep`
and fixes the clock at 2026-10-03 12:00:00.

The credentials, `acct-test` and `key-test`, are fake on purpose: a run that
somehow reached `inventory.dearsystems.com` would be rejected rather than
authenticated against a real account.

`testbench.yaml` names `Hypervel\Saloon\SaloonServiceProvider` first. An
application discovers it from the components manifest; the Testbench skeleton
does not, and it registers the `saloon` HTTP connection the connector sends
over.

### Rate-limit tests

Saloon enforces rate limits only when no fake matched, and records cooldowns
only for responses that came off the wire, so a faked send exercises neither.
[`RateLimitTest`](../tests/Feature/Connector/RateLimitTest.php) therefore calls
the `HasRateLimits` accessors directly, with a `PendingRequest` from
`pendingRequestFor()`:

| Accessor | Pins |
|---|---|
| `resolveRateLimitPolicies()` | one policy, keyed `cin7:api:<accountId>:<digest of the application key>`, the same for every endpoint; none when max or period is not positive |
| `resolveRateLimitStoreName()` | `null` by default, the configured store otherwise |
| `shouldWaitForRateLimits()` | wait for capacity instead of throwing |
| `resolveRateLimitCooldownKeyFor()` | the cooldown is keyed like the window |
| `resolveRateLimitCooldownFor()` | a 429's `Retry-After`, or the configured cooldown (5 seconds by default) without one; the configured cooldown for a 503; `null` for anything else, 200 included, and for a cooldown of `0` |

It also consumes the policy to exhaustion on the suite's default limiter store
(Testbench's `worker-array`) to show the framework limiter really denies on it.
The wait loop itself is not driven: it spins until the window frees up, and a
faked `Sleep` never advances the store's clock.

### Retry tests

[`RetryTest`](../tests/Feature/Connector/RetryTest.php) calls `Sleep::fake()` in
`setUp()` and `Sleep::fake(false)` in `tearDown()`. The real policy waits five
seconds between attempts; faked, the waits take no time and become assertable:

```php
Sleep::assertSequence([
    Sleep::usleep(5_000_000),
    Sleep::usleep(5_000_000),
    Sleep::usleep(5_000_000),
]);
```

The retry policy is read when the request is constructed, so a test that
changes `cin7.retry.*` does so before `new GetCustomer(...)`, not just before
`send()`.

### The Workbench application

`workbench/` is the host application the suite runs against. It owns no models
or migrations: the package defines no tables of its own, and the suite's
limiter store is Testbench's `worker-array`, not the database. What it owns is
the seam a consuming application has:

| Piece | Purpose |
|---|---|
| [`CustomerDirectory`](../workbench/app/Services/CustomerDirectory.php) | A service that takes the connector by constructor injection, bound as a singleton by `WorkbenchServiceProvider`. It proves the package's singleton resolves as a dependency of an application's own service, not only through `$app->make()`. |
| [`Cin7Payloads`](../workbench/app/Support/Cin7Payloads.php) | Fixtures keyed like real Cin7 bodies. `load($path, $name)` reads the V2 reference's examples from `workbench/fixtures/<api path>/<verb>.<request\|response>.json`, and named helpers wrap them: `sale()` (the Sale example, keyed by `ID`), `saleList()`, `saleInvoices()`, `saleInvoicePost()`, `salePayments()` and the rest, plus `customer()`, one record for a list's items. The list envelopes and the Error Model are not here: they are `Cin7Fake`'s, which ships (above), so the suite fakes with what an application does and the coverage gate covers it. |
| [`cin7:customers`](../workbench/app/Console/Commands/ListCustomersCommand.php) | A console command for calling the live API by hand. Its tests prove testbench.yaml's `workbench.discovers.commands` is wired, since it is the only place the console kernel resolves a Workbench service. |

`cin7:customers` lists customers through `CustomerDirectory::all()`, which
walks every page. `--limit` sets the `limit` each page is sent with (10 by
default), not a cap on the total, and `--name` filters by name:

```sh
vendor/bin/testbench cin7:customers --limit=5
```

It needs real credentials (`CIN7_ACCOUNT_ID`, `CIN7_APPLICATION_KEY`) in
`workbench/.env`, which is gitignored; keep it that way. With the test
credentials Cin7 answers `403 Incorrect credentials!`, which shows the auth
headers are going out; the command reports it as `Cin7 responded 403: …` and
exits with status 1.

### `AfterEachTestExtension`

`phpunit.xml` registers the framework's `AfterEachTestExtension`, which
registers every test-state registrar declared in `extra.hypervel.test-state`
(by the installed packages and by the package itself) and flushes static state
after every test. The package declares no registrar, because it holds no
static state. The extension is wired anyway, so the suite runs the same reset
path a consuming application's suite does, and a registrar added later takes
effect without touching `phpunit.xml`; one that is declared but missing fails
the run at bootstrap.
