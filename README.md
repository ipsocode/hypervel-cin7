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
in long-lived Swoole workers: one shared connector, a bounded 503 retry, and
throttling through the framework's rate limiter, all on `hypervel/saloon`.

This is an independent package. It is not affiliated with or endorsed by Cin7.

## Requirements

- PHP 8.4 or newer (CI runs 8.4 and 8.5)
- Hypervel 0.4, which today means `hypervel/components` at `0.4.x-dev`. The
  package requires `hypervel/components` itself rather than its split packages.
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

Every configuration key, with its environment variable and default, is in
[docs/configuration.md](docs/configuration.md).

## Usage

Inject `Cin7Connector` and call a [resource](docs/resources.md) accessor.

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

The connector is registered as a singleton. It holds only readonly scalars and
is never mutated per request, so sharing one instance across coroutines for a
worker's lifetime is safe.

## Documentation

- [docs/configuration.md](docs/configuration.md) — every configuration key with
  its environment variable and default, publishing the config, and when the
  values are read.
- [docs/resources.md](docs/resources.md) — the accessor tree, the conventions
  every resource and request follows, and adding a new resource method.
- [docs/requests.md](docs/requests.md) — the three request bases and the
  request lines they send, the wire protocol, the exceptions a failed call
  throws, and the bounded 503 retry.
- [docs/data.md](docs/data.md) — the typed request and response bodies: the
  conventions every data class follows, the class behind each path, and the
  empty-collection rule.
- [docs/pagination.md](docs/pagination.md) — walking every page of a listing or
  sending the pages concurrently, and how the last page is worked out from
  Cin7's list envelope.
- [docs/connector.md](docs/connector.md) — the shared connector, its transport
  and timeouts, rate limiting and the choice of limiter store, and the 503
  cooldown.
- [docs/testing.md](docs/testing.md) — faking Cin7 in the tests of an
  application that uses the package, and how the package's own suite is built.

## What this package deliberately does not do

- **No caching.** Response caching, cache-key shape and cache-hit logging
  semantics are consumer policy; `Cacheable`/`HasCaching` can be adopted later
  once a second consumer's needs are known.
- **No mandatory DTOs.** Arrays work everywhere: a write takes an array body and
  `json()` returns the decoded array. Typed data objects are an additive layer on
  top ([docs/data.md](docs/data.md)), shown here for a tax rule:

  ```php
  use Ipsocode\Cin7\Data\Ref\Tax\TaxData;

  // Only the keys you set are sent.
  $response = $cin7->ref()->tax()->post(TaxData::from(['Name' => 'VAT', 'Account' => '800']));

  $tax = $response->dto();                  // TaxData
  $rules = $cin7->ref()->tax()->get()->dto(); // list<TaxData>
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
