# hypervel-cin7

A [Cin7 Core](https://www.cin7.com/) API client for
[Hypervel](https://github.com/hypervel/components), built on `hypervel/saloon`.

Cin7 Core, formerly DEAR Inventory, exposes a REST API at `inventory.dearsystems.com`. This
client is safe to run in long-lived Swoole workers: one shared connector, a bounded retry when
Cin7 throttles (429 or 503), and rate limiting through the framework's rate limiter. It is an
independent package, not affiliated with or endorsed by Cin7.

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

## Requirements

- PHP 8.4 or newer (CI runs 8.4 and 8.5)
- Hypervel 0.4, which today means `hypervel/components` at `0.4.x-dev`. The
  package requires `hypervel/components` itself rather than its split packages.
- A Cin7 Core account with API access — its account ID and an application key

## Installation

```sh
composer require ipsocode/hypervel-cin7
```

Your application's `composer.json` must allow Hypervel 0.4's dev branch
(`"minimum-stability": "dev"` with `"prefer-stable": true`). The service provider is
discovered, so there is nothing to register: set `CIN7_ACCOUNT_ID` and `CIN7_APPLICATION_KEY`
in your environment and check them with `php artisan cin7:check`. Publishing the config,
tracking `dev-main` and the nightly check against `0.4.x-dev` are in
[getting started](src/docs/getting-started.md).

## Usage

```php
use Ipsocode\Cin7\Cin7Connector;

public function __construct(private readonly Cin7Connector $cin7) {}

// GET customer?page=1&limit=100
$customers = $this->cin7->customer()->get()->json();
```

## Documentation

The [documentation index](src/docs/index.md) lists every page in reading order. Start with
[getting started](src/docs/getting-started.md), then [resources](src/docs/resources.md) for the
accessor tree and [configuration](src/docs/configuration.md) for every key.

## Contributing

The development setup, the checks CI runs, the coroutine-safety rules every change is held to,
and how releases are cut are in [CONTRIBUTING.md](CONTRIBUTING.md).

## Security

Report security issues privately, as described in [SECURITY.md](.github/SECURITY.md), rather
than in a public issue.

## Credits

The wire protocol, the endpoint table and the page-defaults helper derive from
[`eighteen73/dear-api`](https://github.com/eighteen73/dear-api), by Umair
Mahmood and its contributors, which is MIT-licensed. The client itself is
written on `hypervel/saloon` and is not a copy of that code. LICENSE carries
its copyright notice alongside this package's.

## License

MIT. See [LICENSE](LICENSE).
