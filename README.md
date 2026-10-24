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

public function __construct(private readonly Cin7Connector $cin7) {}

// GET customer?page=1&limit=100
$customers = $this->cin7->customer()->get()->json();
```

## What this is

Cin7 Core — formerly DEAR Inventory — exposes a REST API at
`inventory.dearsystems.com`. This package is a client for it that is safe to run
in long-lived Swoole workers: one shared connector, a bounded retry when Cin7
throttles (429 or 503), and rate limiting through the framework's rate limiter,
all on `hypervel/saloon`.

This is an independent package. It is not affiliated with or endorsed by Cin7.

## Requirements

- PHP 8.4 or newer (CI runs 8.4 and 8.5)
- Hypervel 0.4, which today means `hypervel/components` at `0.4.x-dev`. The
  package requires `hypervel/components` itself rather than its split packages.
- A Cin7 Core account with API access — its account ID and an application key

## Installation

```sh
composer require ipsocode/hypervel-cin7
php artisan vendor:publish --tag=cin7-config
```

Hypervel 0.4 is only available as a dev branch, so your application's
`composer.json` must already allow it: `"minimum-stability": "dev"` together
with `"prefer-stable": true`. Tags are not re-tested as `0.4.x-dev` moves on.
`main` is, every night, by a scheduled check that runs outside this
repository's workflows: while `main` fails against the current `0.4.x-dev`,
an issue titled "Nightly components check failed" is open here. To pick up
changes as they land, require `ipsocode/hypervel-cin7:dev-main` instead. Each release's notes,
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

Every configuration key, with its environment variable and default, is in
[src/docs/configuration.md](src/docs/configuration.md).

## Usage

Inject `Cin7Connector` and call a [resource](src/docs/resources.md) accessor.

```php
use Ipsocode\Cin7\Cin7Connector;

public function __construct(private readonly Cin7Connector $cin7) {}

// GET customer?page=1&limit=100
$all = $this->cin7->customer()->get()->json();

// POST customer, raw JSON body, no page/limit
$new = $this->cin7->customer()->post(['Name' => 'ACME'])->json();

// PUT customer, body carries ID
$this->cin7->customer()->put(['ID' => $guid, 'Name' => 'ACME Ltd']);

// Every customer, across all pages
foreach ($this->cin7->customer()->paginate()->items() as $customer) {
    // …
}
```

Each connector holds only readonly scalars and is never mutated per request, so
sharing one instance across coroutines for a worker's lifetime is safe.
Injecting `Cin7Connector` gives the default connection.

For a second account, such as a sandbox, add it under `connections` in
`config/cin7.php` and ask `Cin7Manager` for it by name:

```php
use Ipsocode\Cin7\Cin7Manager;

$sandbox = app(Cin7Manager::class)->connection('sandbox');
```

Each connection throttles on its own, as
[src/docs/connector.md](src/docs/connector.md#named-connections) describes.

### Checking the setup

`php artisan cin7:check` sends one `GET me` with a connection's credentials
(`--connection=` names one) and prints the company Cin7 answers with, or why
the call failed, without printing either credential; see
[src/docs/connector.md](src/docs/connector.md#checking-a-connection).

## Documentation

- [src/docs/configuration.md](src/docs/configuration.md) — every configuration key with
  its environment variable and default, publishing the config, and when the
  values are read.
- [src/docs/resources.md](src/docs/resources.md) — the accessor tree, the conventions
  every resource and request follows, and adding a new resource method.
- [src/docs/requests.md](src/docs/requests.md) — the three request bases and the
  request lines they send, the wire protocol, the exceptions a failed call
  throws, and the bounded throttling retry.
- [src/docs/data.md](src/docs/data.md) — the typed request and response bodies: the
  conventions every data class follows, the class behind each path, and the
  empty-collection rule.
- [src/docs/pagination.md](src/docs/pagination.md) — walking every page of a listing or
  sending the pages concurrently, and how the last page is worked out from
  Cin7's list envelope.
- [src/docs/connector.md](src/docs/connector.md) — the shared connector, its transport
  and timeouts, rate limiting and the choice of limiter store, the throttling
  cooldown, and checking a connection with `cin7:check`.
- [src/docs/sync.md](src/docs/sync.md) — the optional scheduled copy of Cin7's records
  in one local table: the modules and their order, how a pull works, the
  schedule, the `cin7:sync` command and reading the table.
- [src/docs/testing.md](src/docs/testing.md) — faking Cin7 in the tests of an
  application that uses the package, with the shipped `Cin7Fake` builders for
  list envelopes and the Error Model, and how the package's own suite is built.

## What this package deliberately does not do

- **No caching.** Response caching, cache-key shape and cache-hit logging
  semantics are consumer policy; `Cacheable`/`HasCaching` can be adopted later
  once a second consumer's needs are known. The [sync](src/docs/sync.md), a local
  copy of Cin7's records, is a separate, opt-in table: off by default, it
  creates no table and runs no query.
- **No mandatory DTOs.** Arrays work everywhere: a write takes an array body and
  `json()` returns the decoded array. Typed data objects are an additive layer on
  top ([src/docs/data.md](src/docs/data.md)), shown here for a customer:

  ```php
  use Ipsocode\Cin7\Data\Customer\CustomerPostData;

  // The fields the reference requires must be given; of the rest, only the keys you set are sent.
  $response = $cin7->customer()->post(CustomerPostData::from([
      'Name' => 'ACME',
      'Status' => 'Active',
      'Currency' => 'GBP',
      'PaymentTerm' => '30 days',
      'AccountReceivable' => '610',
      'RevenueAccount' => '200',
      'TaxRule' => 'Tax Exempt',
  ]));

  $customer = $response->dto();                      // CustomerData
  $customers = $cin7->customer()->get()->dto();      // list<CustomerData>
  ```
- **No request logging.** Instrumentation writes consumer-owned models.

## Contributing

The development setup, the checks CI runs, the coroutine-safety rules every
change is held to, and how releases are cut are in
[CONTRIBUTING.md](CONTRIBUTING.md). Report security issues privately, as
described in [SECURITY.md](.github/SECURITY.md), rather than in a public issue.

## Credits

The wire protocol, the endpoint table and the page-defaults helper derive from
[`eighteen73/dear-api`](https://github.com/eighteen73/dear-api), by Umair
Mahmood and its contributors, which is MIT-licensed. The client itself is
written on `hypervel/saloon` and is not a copy of that code. LICENSE carries
its copyright notice alongside this package's.

## License

MIT. See [LICENSE](LICENSE).
